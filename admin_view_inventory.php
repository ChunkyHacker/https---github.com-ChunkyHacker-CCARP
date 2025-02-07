    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inventory Management</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body>
        <div class="container mt-5">
            <h2 class="mb-4">Inventory Management</h2>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addItemModal">Add Item</button>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Structure</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'config.php';
                    $sql = "SELECT * FROM prematerialsinventory";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['structure']}</td>
                                    <td>{$row['name']}</td>
                                    <td>₱{$row['price']}</td>
                                    <td>
                                        <button class='btn btn-warning btn-sm editItem' data-id='{$row['prematerialsinventory_id']}' data-structure='{$row['structure']}' data-name='{$row['name']}' data-price='{$row['price']}' data-bs-toggle='modal' data-bs-target='#editItemModal'>Edit</button>
                                        <button class='btn btn-danger btn-sm deleteItem' data-id='{$row['prematerialsinventory_id']}'>Delete</button>                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>No items found</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
            <button class="btn btn-secondary mt-3" onclick="goBack()">Back</button>
        </div>

        <!-- Add Item Modal -->
        <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addItemModalLabel">Add Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="admin_add_prematerialsitem.php" method="POST">
                            <div class="mb-3">
                                <label for="structure" class="form-label">Structure</label>
                                <input type="text" class="form-control" id="structure" name="structure" required>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" class="form-control" id="price" name="price" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Item Modal -->
        <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editItemForm" action="admin_edit_prematerialsitem.php" method="POST">
                            <input type="hidden" id="editId" name="id">
                            <div class="mb-3">
                                <label for="editStructure" class="form-label">Structure</label>
                                <input type="text" class="form-control" id="editStructure" name="structure" required>
                            </div>
                            <div class="mb-3">
                                <label for="editName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="editName" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="editPrice" class="form-label">Price</label>
                                <input type="number" class="form-control" id="editPrice" name="price" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function goBack() {
                window.location.href = 'admindashboard.php';
            }

            $(document).ready(function() {
                $('.editItem').click(function() {
                    $('#editId').val($(this).data('id'));
                    $('#editStructure').val($(this).data('structure'));
                    $('#editName').val($(this).data('name'));
                    $('#editPrice').val($(this).data('price'));
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('.deleteItem').click(function() {
                    var itemId = $(this).data('id');
                    var confirmation = confirm('Are you sure you want to delete this item?');
                    
                    if (confirmation) {
                        window.location.href = 'admin_delete_prematerialsitem.php?id=' + itemId;
                    }
                });
            });
        </script>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
