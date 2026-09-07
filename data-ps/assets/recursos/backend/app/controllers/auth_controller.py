def login_user(credentials):
    if credentials.get('username') == 'admin':
        return {'status': 'success', 'token': 'mock-jwt-token-xyz'}
    return {'status': 'failed', 'message': 'Invalid credentials'}
