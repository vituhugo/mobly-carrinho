import Api from "./Api";

export default {
    calcularFrete(cep, produtos) {
        return Api.calcularFrete(cep, produtos);
    },

    buscarCep(cep) {
        return Api.buscarCep(cep);
    }
}