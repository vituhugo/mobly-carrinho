function requestResume(type, url, params) {

    if (type === "get") {
        params = {params: params || {}}
    }
    return new Promise ((s, f) => {
        window.axios[type](url, params)
            .then((response) => s(response.data))
            .catch((error) => {
                f(error.response.data.message)
            })
    })
}


export default {
    token(email, senha) {
        let params = { email, senha };
        return requestResume('post', '/api/auth/get-token', params);
    },

    perfil(token) {
        return requestResume('get', '/api/perfil', {token})
    },

    criarCliente(cliente) {
        return requestResume('put', '/api/cliente', cliente);
    },

    criarOrdem(ordem) {
        return requestResume('put', '/api/ordem', ordem);
    },

    calcularFrete(cep, carrinho) {
        cep = cep.replace(/[^0-9]/g, '');
        carrinho = carrinho.map((produto) => { return {id: produto.id, quantidade: produto.quantidade}; });

        return requestResume('post', '/api/calcular-frete', {cep, carrinho});
    },

    buscarCep(cep) {
        cep = cep.replace(/[^0-9]/g, '');
        return requestResume('get', '/api/buscar-cep/'+cep);
    }
}