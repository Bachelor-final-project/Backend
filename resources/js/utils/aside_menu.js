const menus = [
    [
        {
            title: "Dashboard",
            icon: "dashboard",
            to: "dashboard",
            allowedUserTypes: [1,2,3,4,5],
        },
        {
            title: "Entity",
            icon: "users",
            to: "entity.index",
            allowedUserTypes: [1,],
        },
        {
            title: "Users",
            icon: "user",
            to: "user.index",
            allowedUserTypes: [1,],
        },
        {
            title: "Proposals",
            icon: "laptop-file",
            to: "proposal.index",
            allowedUserTypes: [1,2,5],
        },
        {
            title: "Donations",
            icon: "hand-holding-dollar",
            to: "donation.index",
            allowedUserTypes: [1,],
        },
        {
            title: "Donors",
            icon: "handshake-simple",
            to: "donor.index",
            allowedUserTypes: [1,],
        },
        {
            title: "Donations",
            icon: "hand-holding-dollar",
            to: "donation.create",
            allowedUserTypes: [3,],
        },

        // {
        //     title: "Proposals",
        //     icon: "users",
        //     to: "proposal.index",
        //     allowedUserTypes: [5],
        // },
        {
            title: "Beneficiaries",
            icon: "face-smile",
            to: "beneficiary.index",
            allowedUserTypes: [],
        },
        {
            title: "Warehouses",
            icon: "warehouse",
            to: "warehouse.index",
            allowedUserTypes: [1,4],
        },
        {
            title: "Warehouse Transactions",
            icon: "cart-flatbed",
            to: "warehouse_transaction.index",
            allowedUserTypes: [1,4],
        },
        {
            title: "Documentation",
            icon: "photo-film",
            to: "document.index",
            allowedUserTypes: [1,4],
        },
        {
            title: "Items",
            icon: "basket-shopping",
            to: "item.index",
            allowedUserTypes: [1,4],
        },
        {
            title: "Units",
            icon: "ruler",
            to: "unit.index",
            allowedUserTypes: [1,4],
        },
        {
            title: "Currencies",
            icon: "dollar-sign",
            to: "currency.index",
            allowedUserTypes: [1,4],
        },
        
    ],
    [
        {
            title: "dashboard",
            icon: "dashboard",
            to: "dashboard",
        },
    ],
];

// const menu = new Map([
//     [1, Admin],
//     [2, Agent],
// ]);

export default menus;
