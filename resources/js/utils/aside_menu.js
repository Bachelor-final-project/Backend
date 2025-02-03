const menus = [
    [{
        title: "Dashboard",
        icon: "dashboard",
        to: "dashboard",
        allowedUserTypes: [1,2,3,4,5],
    },
     {
        title: "general settings",
        icon: "gear",
        allowedUserTypes: [1,4],
        items: [
            {
                title: "Entities",
                icon: "users",
                to: "entity.index",
                allowedUserTypes: [1,],
            },
            {
                title: "Currencies",
                icon: "dollar-sign",
                to: "currency.index",
                allowedUserTypes: [1,4],
            },
        ]
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
            items: [
                {
                    title: "Proposals",
                    icon: "laptop-file",
                    to: "proposal.index",
                    allowedUserTypes: [1,2,5],
                },
                {
                    title: "new proposal",
                    icon: "laptop-file",
                    to: "proposal.create",
                    allowedUserTypes: [1,5],
                },
            ]
        },
        {
            title: "Donations",
            icon: "hand-holding-dollar",
            to: "donation.index",
            allowedUserTypes: [1,2,],
        },
        {
            title: "Donors",
            icon: "handshake-simple",
            to: "donor.index",
            allowedUserTypes: [1,2,],
        },      
        {
            title: "Documentations",
            icon: "photo-film",
            to: "document.index",
            allowedUserTypes: [1,2,4],
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
            allowedUserTypes: [1],
        },
        {
            title: "Warehouses",
            icon: "warehouse",
            allowedUserTypes: [1,4],
            items: [
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
            ]
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
