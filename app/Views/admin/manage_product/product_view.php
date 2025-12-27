<?php
// echo "<pre>";
// print_r($category_list);
// echo "</pre>";
// exit;
?>
<main id="main" class="main">
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Manage Product</h1>
        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addModal">
            + Add Record
        </button>
    </div>

    <section class="section" style="overflow-x: scroll;">
        <div class="row">
            <div class="col-lg-12 px-0">
                <div class="card">
                    <div class="card-body p-3">
                        <!-- Table with stripped rows -->
                        <table class="table datatable table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Subcategory</th>
                                    <th>Language</th>
                                    <th>Brand</th>
                                    <th>Availability</th>
                                    <th>Price</th>
                                    <th>Details</th>
                                    <th>Image</th>
                                    <th>Stock</th>
                                    <th>Action By</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Active</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tcategory">
                                <tr>
                                    <td colspan="9">
                                        <center id="productResponse">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Product(s) Loading...
                                        </center>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- modals starts -->
    <!-- add Product modal starts -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Product</h5>
                    <button type="button" class="btn-close red-bold " data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Name *</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="addProductName" id="addProductName" maxlength="100" autocomplete="new-name" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Category *</label>
                                <div class="col-sm-12">
                                    <select class="form-select" name="addProductCategory" id="addProductCategory" required>
                                        <option value="">-- Select --</option>
                                        <?=
                                        $category_list = json_encode($category_list);
                                        $category_list = json_decode($category_list);
                                        foreach ($category_list as $category) {
                                            echo '<option value="' . $category->categoryId . '">' . $category->categoryName . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Subcategory *</label>
                                <div class="col-sm-12">
                                    <select class="form-select" name="addProductSubCategory" id="addProductSubCategory" required>
                                        <option value="">-- Select --</option>
                                        <?=
                                        $subcategory_list = json_encode($subcategory_list);
                                        $subcategory_list = json_decode($subcategory_list);
                                        foreach ($subcategory_list as $sub_category) {
                                            echo '<option value="' . $sub_category->subCategoryId . '">' . $sub_category->subCategoryName . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Language *</label>
                                <div class="col-sm-12">
                                    <select class="form-select" name="addProductLanguage" id="addProductLanguage" required>
                                        <option value="">-- Select --</option>
                                        <?=
                                        $language_list = json_encode($language_list);
                                        $language_list = json_decode($language_list);
                                        foreach ($language_list as $language) {
                                            echo '<option value="' . $language->languageId . '">' . $language->languageName . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Brand *</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="addProductBrand" id="addProductBrand" maxlength="50" autocomplete="new-brand" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">code *</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="addProductCode" id="addProductCode" maxlength="50" autocomplete="new-code" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Availability *</label>
                                <div class="col-sm-12">
                                    <input type="number" class="form-control" name="addProductAvailability" id="addProductAvailability" autocomplete="new-availability" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Price *</label>
                                <div class="col-sm-12">
                                    <input type="number" class="form-control" name="addProductPrice" id="addProductPrice" autocomplete="new-price" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Details *</label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" name="addProductDetails" id="addProductDetails" autocomplete="new-details" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Stock *</label>
                                <div class="col-sm-12">
                                    <select class="form-select" name="addProductStock" id="addProductStock" required>
                                        <option value="">-- Select --</option>
                                        <option value="IN_STOCK">In Stock</option>
                                        <option value="OUT_OF_STOCK">Out of Stock</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Active *</label>
                                <div class="col-sm-12">
                                    <select class="form-select" name="addProductActive" id="addProductActive" required>
                                        <option value="">-- Select --</option>
                                        <option value="YES">Yes</option>
                                        <option value="NO">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btnClick">
                                <span id="addProductSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none; pointer-events: none;"></span>
                                <span class="saveProduct" id="saveProductText">Save</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- add Product modal end -->

    <!-- edit Product modal starts -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body" id="editProductBody">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                            <input type="hidden" id="updateProductId" name="updateProductId">
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Name *</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="updateProductName" id="updateProductName" maxlength="100" autocomplete="new-name" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Category *</label>
                                <div class="col-sm-12">
                                    <select class="form-select" name="updateProductCategory" id="updateProductCategory" required>
                                        <option value="">-- Select --</option>
                                        <?=
                                        $category_list = json_encode($category_list);
                                        $category_list = json_decode($category_list);
                                        foreach ($category_list as $category) {
                                            echo '<option value="' . $category->categoryId . '">' . $category->categoryName . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Subcategory *</label>
                                <div class="col-sm-12">
                                    <select class="form-select" name="updateProductSubcategory" id="updateProductSubcategory" required>
                                        <option value="">-- Select --</option>
                                        <?=
                                        $subcategory_list = json_encode($subcategory_list);
                                        $subcategory_list = json_decode($subcategory_list);
                                        foreach ($subcategory_list as $sub_category) {
                                            echo '<option value="' . $sub_category->subCategoryId . '">' . $sub_category->subCategoryName . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Language *</label>
                                <div class="col-sm-12">
                                    <select class="form-select" name="updateProductLanguage" id="updateProductLanguage" required>
                                        <option value="">-- Select --</option>
                                        <?=
                                        $language_list = json_encode($language_list);
                                        $language_list = json_decode($language_list);
                                        foreach ($language_list as $language) {
                                            echo '<option value="' . $language->languageId . '">' . $language->languageName . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Brand *</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="updateProductBrand" id="updateProductBrand" maxlength="50" autocomplete="new-brand" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">code *</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="updateProductCode" id="updateProductCode" maxlength="50" autocomplete="new-code" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Availability *</label>
                                <div class="col-sm-12">
                                    <input type="number" class="form-control" name="updateProductAvailability" id="updateProductAvailability" autocomplete="new-availability" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Price *</label>
                                <div class="col-sm-12">
                                    <input type="number" class="form-control" name="updateProductPrice" id="updateProductPrice" autocomplete="new-price" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Details *</label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" name="updateProductDetails" id="updateProductDetails" autocomplete="new-details" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Stock *</label>
                                <div class="col-sm-12">
                                    <select class="form-select" name="updateProductStock" id="updateProductStock" required>
                                        <option value="">-- Select --</option>
                                        <option value="IN_STOCK">In Stock</option>
                                        <option value="OUT_OF_STOCK">Out of Stock</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Active *</label>
                                <div class="col-sm-12">
                                    <select class="form-select" name="updateProductActive" id="updateProductActive" required>
                                        <option value="">-- Select --</option>
                                        <option value="YES">Yes</option>
                                        <option value="NO">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="editBtnClick">
                                <span id="updateProductSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none; pointer-events: none;"></span>
                                <span class="updateProduct" id="updateProductText">Update</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- edit Product modal end -->

    <!-- delete Product modal starts -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure! you want to delete this Product?</p>
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- delete Product modal end -->
    <!-- modals end -->
</main>

<script type="text/javascript">
    // Add
    $(document).on('click', '.saveProduct', function() {
        const addProductName = $('#addProductName').val().trim();
        const addProductCategory = $('#addProductCategory').val();
        const addProductSubCategory = $('#addProductSubCategory').val();
        const addProductLanguage = $('#addProductLanguage').val();
        const addProductBrand = $('#addProductBrand').val().trim();
        const addProductCode = $('#addProductCode').val().trim();
        const addProductAvailability = $('#addProductAvailability').val();
        const addProductPrice = $('#addProductPrice').val().trim();
        const addProductDetails = $('#addProductDetails').val().trim();
        const addProductStock = $('#addProductStock').val();
        const addProductActive = $('#addProductActive').val();

        $('#btnClick').addClass('disabled');
        $('#saveProductText').text('');
        $('#addProductSpinner').show();

        function stopLoading() {
            $('#addProductSpinner').hide();
            $('#saveProductText').text('Save');
            $('#btnClick').removeClass('disabled');
        }

        if (!addProductName) {
            showToast("Product name is required.", "warning");
            stopLoading();
            return;
        }
        if (!addProductCategory) {
            showToast("Category is required.", "warning");
            stopLoading();
            return;
        }
        if (!addProductSubCategory) {
            showToast("Subcategory is required.", "warning");
            stopLoading();
            return;
        }
        if (!addProductLanguage) {
            showToast("Language is required.", "warning");
            stopLoading();
            return;
        }
        if (!addProductBrand) {
            showToast("Brand is required.", "warning");
            stopLoading();
            return;
        }
        if (!addProductCode) {
            showToast("Code is required.", "warning");
            stopLoading();
            return;
        }
        if (!addProductAvailability) {
            showToast("Availability is required.", "warning");
            stopLoading();
            return;
        }
        if (!addProductPrice) {
            showToast("Price is required.", "warning");
            stopLoading();
            return;
        }
        if (!addProductDetails) {
            showToast("Detail is required.", "warning");
            stopLoading();
            return;
        }
        if (!addProductStock) {
            showToast("Stock status is required.", "warning");
            stopLoading();
            return;
        }
        if (!addProductActive) {
            showToast("Active status is required.", "warning");
            stopLoading();
            return;
        }

        const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
        const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

        const formData = new FormData();
        formData.append('productName', addProductName);
        formData.append('productCategory', addProductCategory);
        formData.append('productSubCategory', addProductSubCategory);
        formData.append('productLanguage', addProductLanguage);
        formData.append('productBrand', addProductBrand);
        formData.append('productCode', addProductCode);
        formData.append('productAvailability', addProductAvailability);
        formData.append('productPrice', addProductPrice);
        formData.append('productDetails', addProductDetails);
        formData.append('productStock', addProductStock);
        formData.append('productActive', addProductActive);
        formData.append(csrfName, csrfHash);

        $.ajax({
            url: "<?= base_url('add-product') ?>",
            type: "POST",
            dataType: "json",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status) {
                    showToast("Product added successfully!", "success");
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    showToast(response.message || "Something went wrong.", "error");
                }
            },
            error: function() {
                showToast("Server error!", "error");
            },
            complete: function() {
                stopLoading();
            }
        });
    });

    // Get All
    $(document).ready(function() {
        $.ajax({
            url: "<?= base_url('fetch-products') ?>",
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    $('.datatable tbody').html(response.html);
                    $('.datatable').DataTable({
                        order: [
                            [13, 'asc']
                        ]
                    });
                } else {
                    $('#productResponse').html(response.message || "No Product(s) found!");
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });

    // Get
    function getProduct(productId) {
        const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
        const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

        $.ajax({
            url: "<?= base_url('get-product-details') ?>",
            type: "POST",
            dataType: "json",
            data: {
                productId: productId,
                [csrfName]: csrfHash
            },
            success: function(response) {
                if (response.status) {
                    const p = response.data;

                    $('#updateProductId').val(p.productId);
                    $('#updateProductName').val(p.productName);
                    $('#updateProductBrand').val(p.productBrand);
                    $('#updateProductCode').val(p.productCode);
                    $('#updateProductAvailability').val(p.productAvailability);
                    $('#updateProductPrice').val(p.productPrice);
                    $('#updateProductDetails').val(p.productDetails);

                    $('#updateProductCategory').val(p.categoryId);
                    $('#updateProductSubcategory').val(p.subCategoryId);
                    $('#updateProductLanguage').val(p.languageId);
                    $('#updateProductStock').val(p.productStock);
                    $('#updateProductActive').val(p.productActive);

                    $('#editModal').modal('show');
                } else {
                    showToast(response.message, "error");
                }
            },
            error: function() {
                showToast("Server error!", "error");
            }
        });
    }

    // Update
    $(document).on('click', '.updateProduct', function() {

        const updateProductId = $('#updateProductId').val();

        const formData = new FormData();
        formData.append('updateProductId', updateProductId);
        formData.append('updateProductName', $('#updateProductName').val().trim());
        formData.append('updateProductCategory', $('#updateProductCategory').val());
        formData.append('updateProductSubCategory', $('#updateProductSubcategory').val());
        formData.append('updateProductLanguage', $('#updateProductLanguage').val());
        formData.append('updateProductBrand', $('#updateProductBrand').val().trim());
        formData.append('updateProductCode', $('#updateProductCode').val().trim());
        formData.append('updateProductAvailability', $('#updateProductAvailability').val());
        formData.append('updateProductPrice', $('#updateProductPrice').val());
        formData.append('updateProductDetails', $('#updateProductDetails').val().trim());
        formData.append('updateProductStock', $('#updateProductStock').val());
        formData.append('updateProductActive', $('#updateProductActive').val());

        const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
        const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();
        formData.append(csrfName, csrfHash);

        $('#editBtnClick').addClass('disabled');
        $('#updateProductText').text('');
        $('#updateProductSpinner').show();

        function stopLoading() {
            $('#updateProductSpinner').hide();
            $('#updateProductText').text('Update');
            $('#editBtnClick').removeClass('disabled');
        }

        if (!$('#updateProductName').val().trim()) {
            showToast("Product name is required.", "warning");
            stopLoading();
            return;
        }
        if (!$('#updateProductCategory').val()) {
            showToast("Category is required.", "warning");
            stopLoading();
            return;
        }
        if (!$('#updateProductSubcategory').val()) {
            showToast("Subcategory is required.", "warning");
            stopLoading();
            return;
        }
        if (!$('#updateProductLanguage').val()) {
            showToast("Language is required.", "warning");
            stopLoading();
            return;
        }
        if (!$('#updateProductBrand').val().trim()) {
            showToast("Brand is required.", "warning");
            stopLoading();
            return;
        }
        if (!$('#updateProductCode').val().trim()) {
            showToast("Code is required.", "warning");
            stopLoading();
            return;
        }
        if (!$('#updateProductAvailability').val()) {
            showToast("Availability is required.", "warning");
            stopLoading();
            return;
        }
        if (!$('#updateProductPrice').val()) {
            showToast("Price is required.", "warning");
            stopLoading();
            return;
        }
        if (!$('#updateProductDetails').val().trim()) {
            showToast("Detail is required.", "warning");
            stopLoading();
            return;
        }
        if (!$('#updateProductStock').val()) {
            showToast("Stock status is required.", "warning");
            stopLoading();
            return;
        }
        if (!$('#updateProductActive').val()) {
            showToast("Active status is required.", "warning");
            stopLoading();
            return;
        }

        $.ajax({
            url: "<?= base_url('update-product') ?>",
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status) {
                    showToast(response.message || "Product updated successfully!", "success");
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(response.message || "Update failed.", "error");
                }
            },
            error: function() {
                showToast("Server error!", "error");
            },
            complete: function() {
                stopLoading();
            }
        });
    });

    // Delete
    function deleteProduct(deleteProductId) {
        $('#deleteModal').modal('show');
        const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
        const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

        $('#confirmDelete').off('click').on('click', function() {
            $.ajax({
                url: "<?= base_url('delete-product') ?>",
                method: "POST",
                data: {
                    deleteProductId: deleteProductId,
                    [csrfName]: csrfHash
                },
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        showToast("Product deleted successfully!", "success");
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        showToast(response.message, "error");
                    }
                },
                error: function() {
                    showToast("Server error!", "error");
                }
            });
        });
    }
</script>