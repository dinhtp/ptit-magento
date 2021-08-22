<?php
declare(strict_types=1);

namespace Master\ImportExport\Model\Data;

use Exception;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Faker\Factory as FakerFactory;

/**
 * Class Customer
 * @package Master\ImportExport\Model\Data
 */
class Customer
{
    /**
     * @var CustomerInterfaceFactory
     */
    protected $customerFactory;

    /**
     * @var AddressInterfaceFactory
     */
    protected $addressFactory;

    /**
     * @var FakerFactory
     */
    protected $fakerFactory;

    /**
     * Customer constructor.
     * @param CustomerInterfaceFactory $customerFactory
     * @param AddressInterfaceFactory $addressFactory
     */
    public function __construct(
        CustomerInterfaceFactory $customerFactory,
        AddressInterfaceFactory $addressFactory
    ) {
        $this->customerFactory = $customerFactory;
        $this->addressFactory = $addressFactory;
    }

    /**
     * @return CustomerInterface
     * @throws Exception
     */
    public function generateCustomer(): CustomerInterface
    {
        $faker = FakerFactory::create();
        $customer = $this->customerFactory->create();

        $address = $this->addressFactory->create();
        $address->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setStreet([$faker->buildingNumber, $faker->streetName])
            ->setCompany($faker->company)
            ->setTelephone($faker->phoneNumber)
            ->setPostcode($faker->postcode)
            ->setCity($faker->city)
            ->setRegionId($faker->state)
            ->setCountryId($faker->countryCode)
            ->setIsDefaultBilling(true)
            ->setIsDefaultShipping(true);

        return $customer->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setEmail($faker->email)
            ->setStoreId(1)
            ->setWebsiteId(1)
            ->setDob(date('Y-m-d H:m:s', random_int(4956331, 951641131)))
            ->setAddresses([$address]);
    }
}
