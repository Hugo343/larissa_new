<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: auth.php');
    exit();
}

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $table = $_POST['table'] ?? '';
    
    switch ($action) {
        case 'delete':
            $id = $_POST['id'];
            $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
            $stmt->execute([$id]);
            header('Location: admin.php?table=' . $table);
            exit;
            break;
            
        case 'add':
        case 'edit':
            $id = $_POST['id'] ?? null;
            $fields = $_POST;
            unset($fields['action'], $fields['table'], $fields['id']);
            
            if ($action === 'add') {
                $columns = implode(', ', array_keys($fields));
                $values = implode(', ', array_fill(0, count($fields), '?'));
                $stmt = $pdo->prepare("INSERT INTO $table ($columns) VALUES ($values)");
                $stmt->execute(array_values($fields));
            } else {
                $set = implode('=?, ', array_keys($fields)) . '=?';
                $stmt = $pdo->prepare("UPDATE $table SET $set WHERE id=?");
                $stmt->execute([...array_values($fields), $id]);
            }
            header('Location: admin.php?table=' . $table);
            exit;
            break;
    }
}

$currentTable = $_GET['table'] ?? 'appointments';

// Fetch table data
$stmt = $pdo->query("SELECT * FROM $currentTable");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch related data for dropdowns
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
$services = $pdo->query("SELECT * FROM services")->fetchAll(PDO::FETCH_ASSOC);
$users = $pdo->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Larissa Salon Studio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --tan-50: #faf8f2;
            --tan-100: #f3eee1;
            --tan-200: #e5dbc3;
            --tan-300: #d4c39d;
            --tan-400: #c7ad7f;
            --tan-500: #b69159;
            --tan-600: #a87e4e;
            --tan-700: #8c6642;
            --tan-800: #72533a;
            --tan-900: #5d4431;
            --tan-950: #312319;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: var(--tan-100);
            min-height: 100vh;
        }

        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: var(--tan-50);
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        .logo {
            color: var(--tan-900);
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: var(--tan-800);
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: all 0.3s;
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--tan-200);
            color: var(--tan-900);
        }

        .nav-link i {
            margin-right: 10px;
            width: 20px;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .content-title {
            color: var(--tan-900);
            font-size: 24px;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: var(--tan-500);
            color: white;
        }

        .btn-primary:hover {
            background: var(--tan-600);
        }

        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--tan-200);
        }

        th {
            color: var(--tan-900);
            font-weight: 600;
            background: var(--tan-50);
        }

        td {
            color: var(--tan-800);
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            margin-right: 5px;
        }

        .edit-btn {
            background: var(--tan-300);
            color: var(--tan-900);
        }

        .delete-btn {
            background: #ff6b6b;
            color: white;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: var(--tan-900);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--tan-300);
            border-radius: 6px;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--tan-500);
        }

        .modal-footer {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <div class="logo">
                <i class="fas fa-spa"></i> Larissa Admin
            </div>
            <nav>
                <a href="?table=appointments" class="nav-link <?php echo $currentTable === 'appointments' ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-alt"></i> Appointments
                </a>
                <a href="?table=services" class="nav-link <?php echo $currentTable === 'services' ? 'active' : ''; ?>">
                    <i class="fas fa-concierge-bell"></i> Services
                </a>
                <a href="?table=categories" class="nav-link <?php echo $currentTable === 'categories' ? 'active' : ''; ?>">
                    <i class="fas fa-tags"></i> Categories
                </a>
                <a href="?table=users" class="nav-link <?php echo $currentTable === 'users' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> Users
                </a>
                <a href="logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </div>
        
        <div class="main-content">
            <div class="content-header">
                <h1 class="content-title">Manage <?php echo ucfirst($currentTable); ?></h1>
                <button class="btn btn-primary" onclick="showAddModal()">
                    <i class="fas fa-plus"></i> Add New
                </button>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <?php
                            if (!empty($rows)) {
                                foreach (array_keys($rows[0]) as $column) {
                                    echo "<th>" . ucfirst($column) . "</th>";
                                }
                                echo "<th>Actions</th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row): ?>
                        <tr>
                            <?php foreach ($row as $value): ?>
                                <td><?php echo htmlspecialchars($value); ?></td>
                            <?php endforeach; ?>
                            <td>
                                <button class="action-btn edit-btn" onclick="showEditModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn delete-btn" onclick="deleteItem(<?php echo $row['id']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Add/Edit -->
    <div id="formModal" class="modal">
        <div class="modal-content">
            <h2 id="modalTitle">Add New Record</h2>
            <form id="recordForm" method="POST">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="table" value="<?php echo $currentTable; ?>">
                <input type="hidden" name="id" id="recordId">
                
                <?php
                if (!empty($rows)) {
                    $columns = array_keys($rows[0]);
                    foreach ($columns as $column) {
                        if ($column !== 'id' && $column !== 'created_at') {
                            echo '<div class="form-group">';
                            echo '<label for="' . $column . '">' . ucfirst($column) . '</label>';
                            
                            if ($column === 'category_id') {
                                echo '<select name="' . $column . '" id="' . $column . '">';
                                foreach ($categories as $category) {
                                    echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                                }
                                echo '</select>';
                            } elseif ($column === 'service_id') {
                                echo '<select name="' . $column . '" id="' . $column . '">';
                                foreach ($services as $service) {
                                    echo '<option value="' . $service['id'] . '">' . $service['name'] . '</option>';
                                }
                                echo '</select>';
                            } elseif ($column === 'user_id') {
                                echo '<select name="' . $column . '" id="' . $column . '">';
                                foreach ($users as $user) {
                                    echo '<option value="' . $user['id'] . '">' . $user['username'] . '</option>';
                                }
                                echo '</select>';
                            } elseif ($column === 'status') {
                                echo '<select name="' . $column . '" id="' . $column . '">';
                                echo '<option value="pending">Pending</option>';
                                echo '<option value="confirmed">Confirmed</option>';
                                echo '<option value="cancelled">Cancelled</option>';
                                echo '</select>';
                            } elseif ($column === 'password') {
                                echo '<input type="password" name="' . $column . '" id="' . $column . '">';
                            } elseif ($column === 'description') {
                                echo '<textarea name="' . $column . '" id="' . $column . '" rows="3"></textarea>';
                            } else {
                                echo '<input type="text" name="' . $column . '" id="' . $column . '">';
                            }
                            
                            echo '</div>';
                        }
                    }
                }
                ?>
                
                <div class="modal-footer">
                    <button type="button" class="btn" onclick="hideModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showAddModal() {
            document.getElementById('modalTitle').textContent = 'Add New Record';
            document.getElementById('formAction').value = 'add';
            document.getElementById('recordId').value = '';
            document.getElementById('recordForm').reset();
            document.getElementById('formModal').classList.add('active');
        }

        function showEditModal(data) {
            document.getElementById('modalTitle').textContent = 'Edit Record';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('recordId').value = data.id;
            
            // Fill form with data
            for (let key in data) {
                const input = document.getElementById(key);
                if (input) {
                    input.value = data[key];
                }
            }
            
            document.getElementById('formModal').classList.add('active');
        }

        function hideModal() {
            document.getElementById('formModal').classList.remove('active');
        }

        function deleteItem(id) {
            if (confirm('Are you sure you want to delete this item?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="table" value="<?php echo $currentTable; ?>">
                    <input type="hidden" name="id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modal when clicking outside
        document.getElementById('formModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideModal();
            }
        });
    </script>
</body>
</html>