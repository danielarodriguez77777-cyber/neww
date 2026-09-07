import datetime

def format_timestamp(dt: datetime.datetime) -> str:
    return dt.strftime('%Y-%m-%d %H:%M:%S')
