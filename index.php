<?php
session_start();
require_once('connection.php');

$rows_per_page = 10;
$pageNum = 1;

if(isset($_GET['page'])) {
    $pageNum = $_GET['page'];
}

$offset = ($pageNum - 1) * $rows_per_page;

$qr = "SELECT COUNT(*) as total FROM gyventojai;";

$row = fetch_record($qr);
$numrows = $row['total'];

$maxpage = ceil($numrows/$rows_per_page);

$self = $_SERVER['PHP_SELF'];
$nav = '';

for($page = 1; $page <= $maxpage; $page++) {
    if($page == $pageNum) {
        $nav .= "<option selected>$page</option>";
    }
    else {
        $nav .= "<option value='". $page . "'>" . $page . "</option>";
    }
}

if($pageNum > 1) {
    $page = $pageNum - 1;
    
    $prev = "<li class='page-item'><a class='page-link' href=\"$self?page=$page\">Ankstesnis</a></li>";
    $first = "<li class='page-item'><a class='page-link' href=\"$self?page=1\">Pirmas</a></li>";
}
else {
    $prev = '&nbsp;';
    $first = '&nbsp;';
}

if($pageNum < $maxpage) {
    $page = $pageNum + 1;
    $next = "<li class='page-item'><a class='page-link' href=\"$self?page=$page\">Kitas</a></li>";
    $last = "<li class='page-item'><a class='page-link' href=\"$self?page=$maxpage\">Paskutinis</a></li>";
}
else {
    $next = '&nbsp;';
    $last = '&nbsp;';
}

include 'head.php';
?>

    <div class="container-fluid">
        <div id="message">
            <?php
            if(isset($_SESSION['success'])) {
                echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
                unset($_SESSION['success']);
            }
            else if(isset($_SESSION['error'])) {
                echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
                unset($_SESSION['error']);
            }
            ?>
        </div>
        <form action="delete.php" method="post">
            <table>
                <tr>
                    <th>
                    <button class="btn btn-primary" name="delete" type="submit">Ištrinti</button>
                    </th>
                    <th></th>
                    <th>Nr.</th>
                    <th>Gimimo metai</th>
                    <th>Gimimo valstybė</th>
                    <th>Lytis</th>
                    <th>Šeimyninė padėtis</th>
                    <th>Vaikų skaičius</th>
                    <th>Seniūnija</th>
                    <th>Gatvė</th>
                    <th>Seniūnijos nr.</th>
                    <th>Teritorijos kodas</th>
                    <th>Gatvės kodas</th>
                    <th>Gatvės ID</th>
                </tr>
                <?php
                    if(isset($_GET['select'])) {
                        
                        $criteria = [];
                        
                        if(!empty($_GET['select_gender']) ) {
                            $criteria[] = "lytis='" . escape_this_string($_GET['select_gender']) . "'";
                            $where = '';
                        }
                        if(!empty($_GET['select_year'])) {
                            $criteria[] = "gimimo_metai='" . escape_this_string($_GET['select_year']) . "'";
                            $where = '';
                        }
                        if(!empty($_GET['select_sen'])) {
                            $criteria[] = "seniunija='" . escape_this_string($_GET['select_sen']) . "'";
                            $where = '';
                        }
                        if(!empty($_GET['select_street'])) {
                            $criteria[] = "gatve='" . escape_this_string($_GET['select_street']) . "'";
                            $where = '';
                        }
                        
                        if(count($criteria) > 0) {
                            $where = ' WHERE ' . implode(' AND ', $criteria);
                        }

                        $sql = "SELECT * FROM gyventojai" . $where ." LIMIT $offset, $rows_per_page;";
                    }
                    else {
                        $sql = "SELECT * FROM gyventojai LIMIT $offset, $rows_per_page;";
                    }
                   
                    $results = fetch_all($sql);
                    
                    $i = ($rows_per_page * ($page - 2)) + 1;
                    foreach($results as $row) {
                        if ($i % 2 == 0) {
                            echo "<tr style='background-color: #bde0ff;'>";
                        }
                        else {
                            echo "<tr>";
                        }
                        echo "<td class='check'><input name='checkbox[]' type='checkbox' value='". $row['id']."'></td>";
                        echo "<td><a href='edit.php?pid=" . $row['id'] . "'><img class='table-image' src='images/edit.png'></a></td>";
                        ?>
                        <td class='id'>
                            <?php
                                echo $i; 
                                $i++;
                            ?>
                        </td>
                        <?php
                        echo "<td>" . $row['gimimo_metai'] . "</td>";
                        echo "<td>" . $row['valstybe'] . "</td>";
                        echo "<td>" . $row['lytis'] . "</td>";
                        echo "<td>" . $row['seimos_padetis'] . "</td>";
                        echo "<td>" . $row['vaikai'] . "</td>";
                        echo "<td>" . $row['seniunija'] . "</td>";
                        echo "<td>" . $row['gatve'] . "</td>";
                        echo "<td>" . $row['seniun_nr'] . "</td>";
                        echo "<td>" . $row['ter_rej_kodas'] . "</td>";
                        echo "<td>" . $row['gatves_kodas'] . "</td>";
                        echo "<td>" . $row['gatves_id'] . "</td>";
                        echo "</tr>";
                    }
                    
                ?>
            </table>
        </form>
        <div class="row">
            <div class="nav-section">
                <nav aria-label="Puslapių navigacija">
                    <ul class="pagination">
                        <?php
                        echo $first . $prev;
                        echo "<select id='page-nav'>" . $nav . "</select>";
                        echo $next . $last;
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <div class="container select-form">
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <div class="filter-head">
                    Filtruoti duomenis pagal:
                </div>
                <form method="get" action="">
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label for="select_year">Gimimo metus</label>
                            <input type="number" class="form-control" name="select_year" id="year" min="1900" max="2017">
                        </div>
                        <div class="col-sm-6">
                            <label for="select_gender">Lytį</label>
                            <select class="form-control" name="select_gender" id="gender">
                                <option disabled selected>Lytį</option>
                                <option></option>
                                <option>V</option>
                                <option>M</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label for="select_sen">Seniūniją</label>
                            <input type="text" class="form-control seniunijos" name="select_sen">
                        </div>
                        <div class="col-sm-6">
                            <label for="select_street">Gatvę</label>
                            <input type="text" class="form-control gatves" name="select_street">
                        </div>
                    </div>        
                    <button type="submit" name="select" id="filter-button" class="btn btn-primary">Filtruoti</button>
                </form>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <script type="text/javascript" src="scripts.js"></script>
    
    <?php include 'select.php'; ?>
</body>
</html>