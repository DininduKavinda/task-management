<!DOCTYPE html>
<html>
<head>
    <title>Task Completed Notification</title>
</head>
<body>
    <h1>Task Completed</h1>
    <p>Dear User,</p>
    <p>The following task has been marked as completed:</p>
    <ul>
        <li><strong>Title:</strong> {{ $title }}</li>
        <li><strong>Description:</strong> {{ $description }}</li>
        <li><strong>Due Date:</strong> {{ $dueDate }}</li>
    </ul>
    <p>Thank you for using our system!</p>
</body>
</html>
