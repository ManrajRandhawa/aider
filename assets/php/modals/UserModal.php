<?php


class UserModal {

    function getAdminModal() {
        return new AdminModal();
    }

    function getCustomerModal() {
        return new CustomerModal();
    }

    function getMerchantModal() {
        return new MerchantModal();
    }

    function getRiderModal() {
        return new RiderModal();
    }

    function getParcelModal() {
        return new ParcelModal();
    }

    function getOrderModal() {
        return new OrderModal();
    }

}