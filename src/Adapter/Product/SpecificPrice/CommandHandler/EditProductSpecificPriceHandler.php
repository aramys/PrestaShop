<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
declare(strict_types=1);

namespace PrestaShop\PrestaShop\Adapter\Product\SpecificPrice\CommandHandler;

use PrestaShop\PrestaShop\Adapter\Product\SpecificPrice\Repository\SpecificPriceRepository;
use PrestaShop\PrestaShop\Core\Domain\Product\SpecificPrice\Command\EditProductSpecificPriceCommand;
use PrestaShop\PrestaShop\Core\Domain\Product\SpecificPrice\CommandHandler\EditProductSpecificPriceHandlerInterface;
use SpecificPrice;

/**
 * Handles @see EditProductSpecificPriceCommand using legacy object model
 */
final class EditProductSpecificPriceHandler implements EditProductSpecificPriceHandlerInterface
{
    /**
     * @var SpecificPriceRepository
     */
    private $specificPriceRepository;

    /**
     * @param SpecificPriceRepository $specificPriceRepository
     */
    public function __construct(
        SpecificPriceRepository $specificPriceRepository
    ) {
        $this->specificPriceRepository = $specificPriceRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(EditProductSpecificPriceCommand $command): void
    {
        $specificPrice = $this->specificPriceRepository->get($command->getSpecificPriceId());

        $this->specificPriceRepository->partialUpdate(
            $specificPrice,
            $this->fillUpdatableProperties($command, $specificPrice)
        );
    }

    /**
     * @param EditProductSpecificPriceCommand $command
     * @param SpecificPrice $specificPrice
     *
     * @return string[]
     */
    private function fillUpdatableProperties(EditProductSpecificPriceCommand $command, SpecificPrice $specificPrice): array
    {
        $updatableProperties = [];
        if (null !== $command->getReduction()) {
            $specificPrice->reduction_type = $command->getReduction()->getType();
            $specificPrice->reduction = (float) (string) $command->getReduction()->getValue();
            $updatableProperties = [
                'reduction_type',
                'reduction',
            ];
        }

        if (null !== $command->includesTax()) {
            $specificPrice->reduction_tax = $command->includesTax();
            $updatableProperties[] = 'reduction_tax';
        }

        if (null !== $command->getPrice()) {
            $specificPrice->price = (float) (string) $command->getPrice();
            $updatableProperties[] = 'price';
        }

        if (null !== $command->getFromQuantity()) {
            $specificPrice->from_quantity = $command->getFromQuantity();
            $updatableProperties[] = 'from_quantity';
        }

        if (null !== $command->getShopGroupId()) {
            $specificPrice->id_shop_group = $command->getShopGroupId();
            $updatableProperties[] = 'id_shop_group';
        }

        if (null !== $command->getShopId()) {
            $specificPrice->id_shop = $command->getShopId();
            $updatableProperties[] = 'id_shop';
        }

        if (null !== $command->getCombinationId()) {
            $specificPrice->id_product_attribute = $command->getCombinationId();
            $updatableProperties[] = 'id_product_attribute';
        }

        if (null !== $command->getCurrencyId()) {
            $specificPrice->id_currency = $command->getCurrencyId();
            $updatableProperties[] = 'id_currency';
        }

        if (null !== $command->getCurrencyId()) {
            $specificPrice->id_country = $command->getCurrencyId();
            $updatableProperties[] = 'id_country';
        }

        if (null !== $command->getCurrencyId()) {
            $specificPrice->id_group = $command->getCurrencyId();
            $updatableProperties[] = 'id_group';
        }

        if (null !== $command->getCustomerId()) {
            $specificPrice->id_customer = $command->getCustomerId();
            $updatableProperties[] = 'id_customer';
        }

        return $updatableProperties;
    }
}
