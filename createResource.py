from genericpath import exists
from os import listdir, system, path
controllers_path = 'app\Http\Controllers\\'
resources_path = 'app\Http\Resources\\'
onlyfiles = [f.removesuffix('Controller.php') for f in listdir(controllers_path) if f != 'Controller.php']
for file in onlyfiles:
    print('Creating Resource for: ',file)
    if path.exists(resources_path + file + 'Resource.php'):
        print(file+'Resource' + 'already exists.. skip')
        continue
    system('php artisan make:resource '+file+'Resource')
    print('Finish.')