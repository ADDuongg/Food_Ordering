<?php
include('../database.php');
$cmd = "SELECT * FROM account";
$result1 = $conn->query($cmd);
$totalRow = $result1->num_rows;

$itemPerPage = 5;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$start_from = ($page - 1) * 5;
/* $select = "SELECT *
FROM account
LIMIT $start_from, $itemPerPage
";
$result_account = $conn->query($select); */
$total_page = ceil($totalRow / $itemPerPage);
$prev_page = ($page == 1) ? $total_page : $page - 1;
$next_page = ($page == $total_page) ? 1 : $page + 1;

$day = date('d');
$active_user = "SELECT COUNT(DISTINCT session) AS unique_sessions FROM sessions;";
$result = $conn->query($active_user);
while ($row = $result->fetch_assoc()) {
    $number_active = $row['unique_sessions'];
}

$sales = "select SUM(totalPrice) as totalprice1 from salefigure where DAY(date) = $day";
$result1 = $conn->query($sales);
while ($row = $result1->fetch_assoc()) {
    $salesfigure = $row['totalprice1'];
    $salesfigure_formatted = number_format($salesfigure, 1);
}
$number = "select SUM(numberSold) as number from salefigure where DAY(date) = $day";
$result2 = $conn->query($number);
while ($row = $result2->fetch_assoc()) {
    $number_sold = $row['number'];
}

$sortOrder = "ASC";
if (isset($_GET['sort']) && $_GET['sort'] === 'DESC') {
    $sortOrder = "DESC";
}

$sql_select = "SELECT * FROM account";

if (isset($_GET['sort']) && $_GET['fieldsort'] === "username") {
    $sql_select .= " ORDER BY username $sortOrder";
    echo '<script>console.log(' . $_GET['sort'] . ')</script>';
}
if (isset($_GET['sort']) && $_GET['fieldsort'] === "age") {
    $sql_select .= " ORDER BY age $sortOrder";
    echo '<script>console.log(' . $_GET['sort'] . ')</script>';
}

$sql_select .= " LIMIT $start_from, $itemPerPage";
$result_account = $conn->query($sql_select);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container-fluid" style="height: 100vh;">
        <div class="row" style="height: 100%;">
            <div class="col-2 navbar-admin text-white" style="padding: 0; position: sticky; left: 0; top: 0; bottom: 0;">
                <div class="div1">
                    <div style="height: 200px; width: 100%;" class="d-flex justify-content-center">
                        <img style="height: 200px; width: 200px;" src="../public/logo.png" alt="...">
                    </div>
                    <div class="detailnav d-flex flex-column align-items-center" style="height: 340px; width: 100%;">
                        <div class="item home"><i class="fa-solid fa-house pe-3"></i> Trang chủ</div>
                        <div class="item revenue"><i class="fa-solid fa-arrow-trend-up pe-3"></i> Quản lý doanh thu</div>
                        <div class="item account"><i class="fa-solid fa-user-tie pe-3"></i> Quản lý tài khoản</div>
                        <div class="item food"><i class="fa-solid fa-bowl-food pe-3"></i> Quản lý món ăn</div>
                        <div class="item order"><i class="fa-solid fa-database pe-3"></i> Quản lý đơn hàng</div>
                        <div class="item data"><i class="fa-solid fa-database pe-3"></i> Thống kê món ăn</div>
                    </div>
                </div>
                <div class="div2" style="height: calc(100% - 540px); position: relative; width: 100%;">
                    <button class="btn text-danger logout" style="position: absolute; bottom: 40px; left: 50%; transform: translateX(-50%);"><i class="fa-solid fa-arrow-right-from-bracket me-3 text-danger"></i>Log out</button>
                </div>
            </div>
            <div class="col-10 detailadmin d-flex flex-column" style="padding: 0; position: relative; height:100%">
                <div class="header border-bottom border-3 d-flex flex-column" style="height: 180px; width: 100%; position:sticky; top: 0">
                    <div class="border-bottom border-1" style="height: 50px;">
                        Dashboard
                    </div>
                    <div class="divdashboard d-flex justify-content-around ">
                        <div class="divactiveuser divdash  border border-1 shadow" style="border-radius: 5%;">
                            <div class="d-flex align-content-center ps-3" style="height: 100%; width: 100%; flex-wrap: wrap;">Số người đang online: <span class="ps-2"><?php echo $number_active ?></span></div>
                        </div>
                        <div class="divrevenue divdash d-flex flex-column border border-1 justify-content-between shadow" style="border-radius: 5%;">
                            <div class="d-flex justify-content-between ps-2 pe-2 pt-1"><span>Số tiền thu được hôm nay</span><i class="fa-solid fa-circle-info" style="padding-top: 5px;"></i></div>
                            <div class="d-flex justify-content-between">
                                <span class="ps-2">Value: <?php echo $salesfigure_formatted ?>$</span>
                                <span class="pe-2">Target today: 1000$</span>
                            </div>
                            <div class="pb-3 ms-2 me-2">
                                <div class="progress " style="width: 100%; ">
                                    <div class="progress-bar" role="progressbar" style="width: <?php echo (($salesfigure * 100) / 1000) ?>%" aria-valuenow="<?php echo $salesfigure ?>" aria-valuemin="0" aria-valuemax="1000"></div>
                                </div>
                            </div>
                        </div>
                        <div class="divsales divdash border border-1 d-flex flex-column border border-1 justify-content-between shadow" style="border-radius: 5%;">
                            <div class="d-flex justify-content-between ps-2 pe-2 pt-1"><span>Hôm nay bán được</span><i class="fa-solid fa-circle-info" style="padding-top: 5px;"></i></div>
                            <div class="d-flex justify-content-between">
                                <span class="ps-2">Number: <?php echo $number_sold ?>$</span>
                                <span class="pe-2">Target today: 1000</span>
                            </div>
                            <div class="pb-3 ms-2 me-2">
                                <div class="progress " style="width: 100%; ">
                                    <div class="progress-bar" role="progressbar" style="width: <?php echo (($number_sold * 100) / 1000) ?>%" aria-valuenow="<?php echo $number_sold ?>" aria-valuemin="0" aria-valuemax="1000"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-1" style="overflow-y: scroll; ">
                    <div class="detailtable" style=" height:100%; width: 100%;">
                        <div class="table-responsive border border-1 table-food" style="width: 100%">
                            <div class="d-flex justify-content-evenly mt-2" style="width: 100%;">
                                <div class="d-flex">
                                    <label style="height: 100%;" for="search">Search: </label>
                                    <input name="search" type="search" class="form-control ms-2">
                                </div>
                                <div class="text-end"><button class="btn btn-success btnAddAccount">Add Account</button></div>
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Avatar</th>
                                        <th scope="col">Name<a href="account.php?page=<?php echo isset($page) ? $page : ''; ?>&sort=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>&fieldsort=username">
                                                <i style="cursor: pointer;" class="fa-solid fa-sort-<?php echo $sortOrder === 'ASC' ? 'up' : 'down'; ?>"></i>
                                            </a>
                                        </th>

                                        <th scope="col">Email</th>
                                        </th>
                                        <th scope="col">role</th>
                                        <th scope="col">DateOfBirth</th>
                                        <th scope="col">Age<a href="account.php?page=<?php echo isset($page) ? $page : ''; ?>&&sort=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>&&fieldsort=age">
                                                <i style="cursor: pointer;" class="fa-solid fa-sort-<?php echo $sortOrder === 'ASC' ? 'up' : 'down'; ?>"></i>
                                            </a>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="t-body">
                                    <?php while ($row = $result_account->fetch_assoc()) :
                                    ?>
                                        <tr class="tr-data">
                                            <td><?php echo $row['user_id']; ?></td>
                                            <td><img style="height: 50px; width: 50px;" src="../avatar/<?php echo $row['avatar'] ? $row['avatar'] : 'default.png' ?>" alt=""></td>
                                            <td><?php echo $row['username']; ?></td>
                                            <td><?php echo $row['email']; ?></td>
                                            <td><?php echo $row['role']; ?></td>
                                            <td><?php echo $row['dateOfBirth']; ?></td>
                                            <td><?php echo $row['age']; ?></td>
                                            <td>
                                                <div class="d-flex " style="height: 100%; width: 100%; flex-wrap: wrap;">
                                                    <form method="GET" action="updateAccountForm.php">
                                                        <input type="hidden" name="id" value="<?php echo $row['user_id']; ?>">
                                                        <input type="hidden" name="action" value="update">
                                                        <button type="submit" class="btn  btnUpdate" style="height: auto; width: auto;">
                                                            <i class="fa-solid fa-pen-to-square text-primary" style="height: 100%; width: 100%;"></i>
                                                        </button>
                                                    </form>

                                                    <form method="GET" action="deleteAccount.php?action=delete">
                                                        <input type="hidden" name="id" value="<?php echo $row['user_id']; ?>">
                                                        <input type="hidden" name="image" value="<?php echo $row['avatar']; ?>">
                                                        <input type="hidden" name="action" value="delete">
                                                        <button type="submit" class="btn  btnDelete" style="height: auto; width: auto;">
                                                            <i class="fa-solid fa-trash text-danger" style="height: 100%; width: 100%;"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>

                            <div class="d-flex justify-content-between">
                                <span>Show <?php echo $page ?> of <?php echo $total_page ?> pages</span>
                                <nav aria-label="Page navigation example ">
                                    <ul class="pagination">
                                        <li class="page-item"><a class="page-link" href="foodadmin.php?page=<?php echo $prev_page; ?>">Previous</a></li>
                                        <?php
                                        for ($i = 1; $i <= $total_page; $i++) {
                                            echo '
                  <li class="page-item"><a class="page-link" href="foodadmin.php?page=' . $i . '">' . $i . '</a></li>
                  ';
                                        }
                                        ?>
                                        <li class="page-item"><a class="page-link" href="foodadmin.php?page=<?php echo $next_page; ?>">Next</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const inputSearch = document.querySelector('input[name="search"]');
        const table = document.getElementById('account-table');
        var tr_Data = document.querySelector('.tr-data')
        var tbody = document.querySelector('.t-body')
        // Hàm cập nhật bảng dữ liệu
        function updateTable(data) {
            // Tạo một biến để chứa nội dung cần cập nhật
            let tableContent = '';

            data.forEach(function(account) {
                tableContent += `
       <tr>
            <td>${account.user_id}</td>
            <td><img style="height: 50px; width: 50px;" src="../avatar/${account.avatar || 'default.png'}" alt=""></td>
            <td>${account.username}</td>
            <td>${account.email}</td>
            <td>${account.role}</td>
            <td>${account.dateOfBirth}</td>
            <td>${account.age}</td>
            <td>
                <div class="d-flex" style="height: 100%; width: 100%; flex-wrap: wrap;">
                    <form method="GET" action="updateAccountForm.php">
                        <input type="hidden" name="id" value="${account.user_id}">
                        <input type="hidden" name="action" value="update">
                        <button type="submit" class="btn btnUpdate" style="height: auto; width: auto;">
                            <i class="fa-solid fa-pen-to-square text-primary" style="height: 100%; width: 100%;"></i>
                        </button>
                    </form>
                    <form method="GET" action="deleteAccount.php?action=delete">
                        <input type="hidden" name="id" value="${account.user_id}">
                        <input type="hidden" name="image" value="${account.avatar}">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="btn btnDelete" style="height: auto; width: auto;">
                            <i class="fa-solid fa-trash text-danger" style="height: 100%; width: 100%;"></i>
                        </button>
                    </form>
                </div>
            </td>
            </tr>
        `;
            });

            // Gán nội dung mới vào bảng
            tbody.innerHTML = tableContent;
        }


        inputSearch.addEventListener('input', function() {
            const value_tmp = inputSearch.value;
            fetch(`../controller_old/search.php?search=${value_tmp}`, {})
                .then((res) => res.json())
                .then((data) => {
                    /* console.log(data.username); */
                    console.log(data);
                    updateTable(data); // Cập nhật bảng dữ liệu khi có dữ liệu mới
                })
                .catch((err) => {
                    console.log(err);
                });
        });





        var btnAdd_account = document.querySelector('.btnAddAccount')
        btnAdd_account.addEventListener('click', function() {
            console.log(123);
            window.location.href = "./addAccountForm.php"
        })

        var logoutadmin = document.querySelector('.logout');
        console.log(logoutadmin);
        logoutadmin.addEventListener('click', function() {
            window.location.href = "../login.php"
        })
    </script>
    <script type="module" src="../js/admin.js">
    </script>
</body>

</html>