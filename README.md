# Money Flow

Este projeto baseia-se no conceito de uma carteira digital. Possui dois tipos de usuários (client e shopkeeper). ambos têm carteira com dinheiro e realizam transferências entre eles. Entretanto, o usuário do tipo shopkeeper pode apenas receber transferências, enquanto o usuário do tipo client pode receber de outros usuários deste tipo e transferir para ambos os tipos.

As operações de transferência possuem quatro status e sofrem modificações ao longo do processo. Quando iniciada, a transação encontra-se em análise (status 1). Após a aprovação do serviço autorizador externo, a transação receberá o status pago (status 2) e o dinheiro sairá de uma conta para a outra. Em caso de não autorizado pelo servico autorizador externo, a transação receberá o status de estornado (status 4) e nenhuma ação será feita. Caso seja realizado um estorno, a transação receberá o status de cancelado e o dinheiro retornada para a conta de origem.
#### Relação do status:
 - IN_ANALYSIS = 1 (pagamento em análise)
 - PAID = 2 (pago)
 - REFUNDED = 3 (estornado)
 - CANCELED = 4 (cancelado)