import uuid

class PaymentGateway:
    def process_charge(self, amount: float) -> str:
        transaction_id = str(uuid.uuid4())
        print(f'Charged ${amount} via MockGateway. TxID: {transaction_id}')
        return transaction_id
