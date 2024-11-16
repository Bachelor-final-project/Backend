from os import system

models = ['Positions', 'Organizations', 'Store_details', 'UserSection','TimeDate', 'AbsenseRequest', 'Time', 'Notification',]
models = [
    "User",
    "UserRole",
    "Role",
    "Permission",
    "RolePermission",
    "Unit",
    "Warehouse",
    "WarehouseDetail",
    "Beneficiary",
    "Proposal",
    "ProposalDetail",
    "ProposalBeneficiary",
    "Attachment",
    "Log"
]

for model in models:
    print('Creating Model for: ',model)
    system('php artisan make:model '+model+' -a --api')
    print('Done.')