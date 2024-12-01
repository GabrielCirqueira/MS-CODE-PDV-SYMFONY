<?php 

namespace App\Service;

use App\Repository\CarrinhoRepository;
use App\Repository\ProdutoRepository;
use App\Repository\ItemRepository;
use App\Entity\Item;

class AdicionarProdutosCarrinhoService
{
    private $carrinhoRepository;
    private $produtoRepository;
    private $itemRepository;

    public function __construct(
        CarrinhoRepository $carrinhoRepository, 
        ProdutoRepository $produtoRepository,
        ItemRepository $itemRepository
    ) {
        $this->carrinhoRepository = $carrinhoRepository;
        $this->produtoRepository = $produtoRepository;
        $this->itemRepository = $itemRepository;
    }

    public function execute(int $idCarrinho, array $produtos): void
    {
        $carrinho = $this->carrinhoRepository->find($idCarrinho);

        $valorCarrinho = $carrinho->getValorTotal();

        foreach ($produtos as $produtoData) {
            $produto = $this->produtoRepository->find($produtoData["id"]);

            $itemExistente = $this->itemRepository->findOneBy([
                'produto' => $produto,
                'carrinho' => $carrinho
            ]);

            if ($itemExistente) {

                $novaQuantidade = $itemExistente->getQuantidade() + $produtoData['quantidade'];
                $itemExistente->setQuantidade($novaQuantidade);
                $itemExistente->setValor($novaQuantidade * $produto->getValorUnitario());
                $this->itemRepository->salvar($itemExistente);
                $valorCarrinho += $itemExistente->getValor();

            } else {

                $quantidade = $produtoData['quantidade'];
                $valorUnitario = $produto->getValorUnitario();
                $valorTotalItem = $quantidade * $valorUnitario;

                $item = new Item($produto, $carrinho, $quantidade, $valorTotalItem);
                $this->itemRepository->salvar($item);
                echo $produtoData["id"] . PHP_EOL . $valorTotalItem . PHP_EOL;
                $valorCarrinho += $valorTotalItem;
            }
        }

        $carrinho->setValorTotal($valorCarrinho);
        $carrinho->setAtualizadoEm(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));
        $carrinho->setStatus("Aguardando Pagamento");
        $this->carrinhoRepository->salvar($carrinho);
    }
}
