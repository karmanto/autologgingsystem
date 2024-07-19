import json
import os
import time
from telegram import Update, Bot
from telegram.ext import Application, CommandHandler, ContextTypes
from dotenv import load_dotenv
import logging

# Load environment variables
load_dotenv()

# Configure logging
logging.basicConfig(
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    level=logging.INFO
)
logger = logging.getLogger(__name__)

# Set up configurations
if os.getenv('FAKE_CONFIG', 'false').lower() == 'true':
    import fake_config as config
else:
    import config

# Set up constants
TELEGRAM_TOKEN = os.getenv("TELEGRAM_BOT_TOKEN")
SETTINGS_JSON_PATH = config.SETTINGS_JSON_PATH
MONITOR_JSON_PATH = config.INFO_JSON_PATH
USER_JSON_PATH = config.USER_JSON_PATH

bot = Bot(token=TELEGRAM_TOKEN)

# Helper functions to read and write JSON files
def read_json(file_path):
    if not os.path.exists(file_path):
        return {}
    with open(file_path, 'r') as file:
        return json.load(file)

def write_json(file_path, data):
    with open(file_path, 'w') as file:
        json.dump(data, file, indent=4)

# Asynchronous function to handle the /start command
async def start(update: Update, context: ContextTypes.DEFAULT_TYPE) -> None:
    user = update.effective_user
    await update.message.reply_text(f'Hi {user.first_name}! Use /notify to toggle notifications.')

# Asynchronous function to handle the /notify command
async def notify(update: Update, context: ContextTypes.DEFAULT_TYPE) -> None:
    user = update.effective_user
    data = read_json(USER_JSON_PATH)

    if not data:
        data = {"user_notifications": []}

    user_found = False
    for user_notif in data["user_notifications"]:
        if user_notif["user_id"] == str(user.id):
            user_notif["receive_notifications"] = not user_notif["receive_notifications"]
            user_found = True
            await update.message.reply_text(f'Notifications {"enabled" if user_notif["receive_notifications"] else "disabled"}.')
            break

    if not user_found:
        data["user_notifications"].append({"user_id": str(user.id), "receive_notifications": True})
        await update.message.reply_text('Notifications enabled.')

    write_json(USER_JSON_PATH, data)

# Function to send notifications
def send_notification(message):
    data = read_json(USER_JSON_PATH)
    user_notifications = data.get("user_notifications", [])

    for user_notif in user_notifications:
        if user_notif["receive_notifications"]:
            while True:
                try:
                    bot.send_message(chat_id=user_notif["user_id"], text=message)
                    break
                except Exception as e:
                    logger.error(f"Error sending message: {e}")
                    time.sleep(5)  # Wait before retrying

# Asynchronous function to monitor changes
async def monitor_changes(context: ContextTypes.DEFAULT_TYPE) -> None:
    previous_data = read_json(MONITOR_JSON_PATH)
    settings_data = read_json(SETTINGS_JSON_PATH)
    show_list = settings_data.get('show_list', [])
    name_dict = {item['field']: item['fullname'] for item in settings_data.get('name_list', [])}

    current_data = read_json(MONITOR_JSON_PATH)

    for show_item in show_list:
        field = show_item['field']
        if show_item['stat'] and previous_data.get(field) != current_data.get(field):
            message = f"Status {name_dict.get(field, field)} berubah menjadi {'aktif' if current_data.get(field) else 'nonaktif'}."
            send_notification(message)

    previous_data = current_data

# Main function to run the bot
def main() -> None:
    application = Application.builder().token(TELEGRAM_TOKEN).build()

    application.add_handler(CommandHandler("start", start))
    application.add_handler(CommandHandler("notify", notify))

    job_queue = application.job_queue
    job_queue.run_repeating(monitor_changes, interval=5)

    application.run_polling()

if __name__ == '__main__':
    main()
