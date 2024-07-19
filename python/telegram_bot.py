import json
import os
import time
from telegram import Update, Bot
from telegram.ext import Updater, CommandHandler, CallbackContext
from dotenv import load_dotenv
from telegram.error import NetworkError, Unauthorized, RetryAfter

load_dotenv()

if os.getenv('FAKE_CONFIG', 'false').lower() == 'true':
    import fake_config as config
else:
    import config

TELEGRAM_TOKEN = os.getenv("TELEGRAM_BOT_TOKEN")
SETTINGS_JSON_PATH = config.SETTINGS_JSON_PATH
MONITOR_JSON_PATH = config.INFO_JSON_PATH
USER_JSON_PATH = config.USER_JSON_PATH

bot = Bot(token=TELEGRAM_TOKEN)

def read_json(file_path):
    if not os.path.exists(file_path):
        return {}
    with open(file_path, 'r') as file:
        return json.load(file)

def write_json(file_path, data):
    with open(file_path, 'w') as file:
        json.dump(data, file, indent=4)

def start(update: Update, context: CallbackContext) -> None:
    user = update.effective_user
    update.message.reply_text(f'Hi {user.first_name}! Use /notify to toggle notifications.')

def notify(update: Update, context: CallbackContext) -> None:
    user = update.effective_user
    data = read_json(USER_JSON_PATH)
    settings_data = read_json(SETTINGS_JSON_PATH)

    if not data:
        data = {"user_notifications": []}

    user_found = False
    for user_notif in data["user_notifications"]:
        if user_notif["user_id"] == str(user.id):
            user_notif["receive_notifications"] = not user_notif["receive_notifications"]
            user_found = True
            update.message.reply_text(f'Notifications {"enabled" if user_notif["receive_notifications"] else "disabled"}.')
            break

    if not user_found:
        data["user_notifications"].append({"user_id": str(user.id), "receive_notifications": True})
        update.message.reply_text('Notifications enabled.')

    write_json(USER_JSON_PATH, data)

def send_notification(message):
    data = read_json(USER_JSON_PATH)
    user_notifications = data.get("user_notifications", [])

    for user_notif in user_notifications:
        if user_notif["receive_notifications"]:
            while True:
                try:
                    bot.send_message(chat_id=user_notif["user_id"], text=message)
                    break
                except NetworkError:
                    time.sleep(5)
                except Unauthorized:
                    break
                except RetryAfter as e:
                    time.sleep(e.retry_after)
                except Exception:
                    time.sleep(5)

def monitor_changes():
    previous_data = read_json(MONITOR_JSON_PATH)
    settings_data = read_json(SETTINGS_JSON_PATH)
    show_list = settings_data.get('show_list', [])
    name_dict = {item['field']: item['fullname'] for item in settings_data.get('name_list', [])}

    while True:
        time.sleep(1)
        current_data = read_json(MONITOR_JSON_PATH)

        for show_item in show_list:
            field = show_item['field']
            if show_item['stat'] and previous_data.get(field) != current_data.get(field):
                message = f"Status {name_dict.get(field, field)} berubah menjadi {'aktif' if current_data.get(field) else 'nonaktif'}."
                send_notification(message)

        previous_data = current_data

def main() -> None:
    updater = Updater(TELEGRAM_TOKEN)
    dispatcher = updater.dispatcher

    dispatcher.add_handler(CommandHandler("start", start))
    dispatcher.add_handler(CommandHandler("notify", notify))

    updater.start_polling()

    monitor_changes()

    updater.idle()

main()
