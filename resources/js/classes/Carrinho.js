import Storage from "../servicos/Storage";
import Notify from "./Notify";
import Api from "../servicos/Api";

export default {

    _carrinho: Storage.get('carrinho', []),

    items() { return Storage.get('carrinho', []) },

    addCarrinho (produto) {

        if (produto.estoque < produto.quantidade) {
            Notify.warning('Quantidade desejada acima do estoque disponível.');
            return;
        }

        if (produto.quantidade === 0) {
            Notify.warning('Selecione a quantidade desejada.');
            return;
        }

        let produto_no_carrinho = this._carrinho.find((p) => p.id == produto.id);

        if (produto_no_carrinho) {

            produto_no_carrinho.quantidade = +produto_no_carrinho.quantidade + (+produto.quantidade);

            Notify.info('Adicionado ao Carrinho, '+produto.quantidade+"x "+ produto.nome+", total:"+produto_no_carrinho.quantidade);
            this._refresh();
            return;
        }

        this._carrinho.push(produto);

        Notify.info('Adicionado ao Carrinho, '+produto.quantidade+"x "+ produto.nome);

        this._refresh();
    },

    removerProduto(produto) {
        let index = this._carrinho.findIndex((prod) => prod.id === produto.id);

        let carrinho = this._carrinho;
        carrinho.splice(index, 1);
        this._carrinho = carrinho;

        Notify.info('O item: '+ produto.nome + " foi removido de seu carrinho.");
        this._refresh()
    },

    atualizarProduto(produto, quantidade) {

        if (+produto.quantidade === 0) {
            this.removerProduto(produto);
            return;
        }

        //Pega a referencia do objeto
        produto = this._carrinho.find((prod) => prod.id === produto.id);
        produto.quantidade = quantidade;

        this._refresh();
    },

    clear() {
        this._carrinho = [];
        this._refresh();
    },

    _refresh() {
        Storage.set('carrinho', this._carrinho);
    }
}