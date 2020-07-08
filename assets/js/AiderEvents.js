class AiderEvents {
    FindRiderEvent(userID, transactionID, transactionType) {
        var e = new CustomEvent(
            'findriderevent',
            {
                details: {
                    user: userID,
                    transaction: transactionID,
                    transactionType: transactionType
                },
                bubbles: true,
                cancelable: true
            });
    }
}