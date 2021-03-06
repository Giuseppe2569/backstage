<?php
require __DIR__. '/__connect_db.php';
$pname = 'listActivity'; // 自訂的頁面名稱

$per_page = 5; //每頁有幾筆
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // 第幾頁

$t_sql = "SELECT COUNT(1) FROM active";
$total_rows = $pdo->query($t_sql)->fetch()[0]; //總筆數
$total_pages = ceil($total_rows/$per_page); //總頁數

// 限定頁碼範圍
if($page<1){
    header('Location: ab_listActivity.php');
    exit;
}
if($page>$total_pages){
    header('Location: ab_listActivity.php?page='. $total_pages);
    exit;
} 

$sql = sprintf("SELECT * FROM active ORDER BY actSid DESC LIMIT %s, %s",($page-1)*$per_page, $per_page);
$stmt = $pdo->query($sql);
?>
<?php include __DIR__. '/__html_head.php'; ?>
<?php include __DIR__. '/__navbar.php'; ?>
<div class="container" style="margin-top: 20px">

    <nav aria-label="Page navigation example">
        <ul class="pagination">
<!--            <li class="page-item"><a class="page-link" href="#">Previous</a></li>-->

            <?php for($i=1; $i<=$total_pages; $i++):?>
            <li class="page-item <?= $i==$page ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?=$i?>"><?=$i?></a>
            </li>
            <?php endfor ?>

<!--            <li class="page-item"><a class="page-link" href="#">Next</a></li>-->
        </ul>
    </nav>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th scope="col">修改</th>
            <th scope="col">#</th>
            <th scope="col">活動名稱</th>
            <th scope="col">活動創立人</th>
            <th scope="col">運動項目</th>
            <th scope="col">開始時間</th>
            <th scope="col">結束時間</th>
            <th scope="col">性別限制</th>
            <th scope="col">報名截止時間</th>
            <th scope="col">人數上限</th>
            <th scope="col">城市</th>
            <th scope="col">區域</th>
            <th scope="col">地址</th>
            <th scope="col">地點</th>
            <th scope="col">del</th>
        </tr>
        </thead>
        <tbody>
        <?php while($r = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><a href="ab_edit_act.php?actSid=<?= $r['actSid'] ?>"><i class="fas fa-edit"></i></a></td>
            <th scope="row"><?= $r['actSid'] ?></th>
            <td><?= $r['actName'] ?></td>
            <td><?= $r['memSid'] ?></td>
            <td><?= $r['actSport'] ?></td>
            <td><span class="JQellipsis"><?= $r['actTimeUp'] ?></span></td>
            <td><span class="JQellipsis"><?= $r['actTimeEnd'] ?></span></td>
            <td><?= $r['actGender'] ?></td>
            <td><span class="JQellipsis"><?= $r['actCutoff'] ?></span></td>
            <td><?= $r['actPleNum'] ?></td>
            <td><?= $r['actCity'] ?></td>
            <td><?= $r['actArea'] ?></td>
            <td><?= $r['actAddress'] ?></td>
            <td><?= $r['plaName'] ?></td>
            <td><a href="javascript:del_it(<?= $r['actSid'] ?>)"><i class="fas fa-trash-alt"></i></a></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item <?= $page==1 ? 'disabled' : ''; ?>"><a class="page-link" href="?page=1">&lt;&lt;</a></li>
            <li class="page-item <?= $page==1 ? 'disabled' : ''; ?>"><a class="page-link" href="?page=<?= $page-1 ?>">&lt;</a></li>
            <li class="page-item"><a class="page-link"><?= $page. '/'. $total_pages ?></a></li>
            <li class="page-item <?= $page==$total_pages ? 'disabled' : ''; ?>"><a class="page-link" href="?page=<?= $page+1 ?>">&gt;</a></li>
            <li class="page-item <?= $page==$total_pages ? 'disabled' : ''; ?>"><a class="page-link" href="?page=<?= $total_pages ?>">&gt;&gt;</a></li>
        </ul>
    </nav>
</div>
<script>
        function del_it(actSid){
            if(confirm('你確定要刪除編號為 '+actSid+' 的資料嗎?')){
                location.href = 'ab_del.php?actSid=' + actSid;
            }
        }

        $(function(){
            var len = 10; // 超過?個字以"..."取代
            $(".JQellipsis").each(function(i){
                if($(this).text().length>len){
                    $(this).attr("title",$(this).text());
                    var text=$(this).text().substring(0,len-1)+"...";
                    $(this).text(text);
                }
            });
        });
</script>
<?php include __DIR__. '/__html_foot.php';