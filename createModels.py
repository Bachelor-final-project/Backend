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
    "Log",
    "Item"
]

for model in models:
    print('Creating Model for: ',model)
    system(f'php artisan make:model {model} -a')
    print('Done.')