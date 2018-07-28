<?php
namespace App\Repositories\PackageInfo;
 
interface PackageInfoRepositoryContract
{
    
    public function find($id);
    
    public function getAllPackageInfo();

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);

    // public function getChildCategoryByBrandId($brand_id);
}
