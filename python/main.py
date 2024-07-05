import MySQLdb
from dotenv import load_dotenv
import os

load_dotenv()

# Konfigurasi koneksi ke database
db_config = {
    'host': os.getenv('DB_HOST'),
    'user': os.getenv('DB_USER'),
    'password': os.getenv('DB_PASSWORD'),
    'database': os.getenv('DB_NAME'),
}

try:
    # Membuat koneksi ke database
    conn = MySQLdb.connect(**db_config)

    # Menggunakan koneksi untuk melakukan sesuatu
    cursor = conn.cursor()
    cursor.execute("SELECT VERSION()")
    data = cursor.fetchone()
    print(f"Database version: {data}")

    # Menutup koneksi
    conn.close()

except MySQLdb.Error as e:
    print(f"Error connecting to MySQL database: {e}")
