<?php require_once( 'checkLogin.php'); restrictSupervisor($USER); ?>
<?php include 'header.php'; ?>

<style>
table {
    width: 100%;
}
table td {
    padding: 10px;
}
table tr:nth-child(odd) td {
    background-color: rgba(254, 188, 27, 0.05);
}
table tr:first-child td {
    background-color: #febc1b;
    color: white;
    
    font-size: 1.1em;
}

table tr:first-child {
    border-bottom: none;
}

table tr:last-child {
    border-bottom: none;
}

table tr {
    border-bottom: 1px solid rgba(0,0,0,0.2);
}

</style>


<h2>List of tutors</h2>
<table>
    <tr>
        <td>ID</td>
        <td>Name</td>
        <td>Email</td>
        <td>Phone</td>
        <td>Bio</td>
    </tr>
<?php
    $tutors = $userManager->getTutors();
    foreach ($tutors as $t) {
        echo '
            <tr>
                <td>'.$t->getID().'</td>
                <td>'.$t->getName().'</td>
                <td>'.$t->getEmail().'</td>
                <td>'.$t->getPhone().'</td>
                <td>'.$t->getBio().'</td>
            </tr>';
    }

?>
</table>
<?php include 'footer.php'; ?>
