<?php
namespace App\GraphQL\Mutation;

use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use GraphQL\Type\Definition\Type;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

class ProductMutation implements MutationInterface, AliasedInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function type(): Type
    {
        return Type::nonNull(GraphQL::type('Product'));
    }

    public function createProduct(Argument $args)
    {
        $input = $args['input'];
        
        $product = new Product();
        $product->setName($input['name']);
        $product->setPrice($input['price']);
        $product->setSlug($input['slug']);
        $product->setDescription($input['description']);

        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }

    public static function getAliases(): array
    {
        return [
            'createProduct' => 'create_product'
        ];
    }
}