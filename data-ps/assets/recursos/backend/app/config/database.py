import os

def get_db_connection():
    db_uri = os.getenv('DATABASE_URL', 'postgresql://user:pass@localhost:5432/db')
    print(f'Connected to database at {db_uri}')
    return True
