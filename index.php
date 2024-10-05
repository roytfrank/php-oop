<?php require_once __DIR__."/header.php"; ?>


<table>
    <thead>
    <tr>
        <th>Report Type</th>
        <th>Message</th>
        <th>Link</th>
        <th>Email</th>
        <th>Created At</th>
    </tr>
    </thead>
    <tbody>
    <?php if(isset($bugReports)): ?>
    <?php  foreach($bugReports as $report): ?>
        <tr>
          <td><?php echo $report->getReportType(); ?></td>
          <td><?php echo $report->getEmail(); ?></td>
          <td><?php echo $report->getMessage(); ?></td>
          <td><?php echo $report->getLink(); ?></td>
          <td><?php echo $report->getCreatedAt(); ?></td>
          <td>
          <a href="#update-<?php echo $report->getId(); ?>">Update</a>
          <a href="#delete-<?php echo $report->getId(); ?>">Delete</a>
          </td>
      </tr>
    <?php  endforeach;  ?>

     <?php endif;?>
    </tbody>
</table>