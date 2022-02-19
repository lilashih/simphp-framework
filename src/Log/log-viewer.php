<!DOCTYPE html>
<html>

<head>
    <title><?php echo config('log.viewer.title') ?></title>
    <link href="/vendors/bootstrap/bootstrap.min.css" rel="stylesheet">
    <style>
        .row {
            margin-left: 0;
            margin-right: 0;
            height: 100vh;
        }

        a {
            color: #686e76;
        }

        .active a, a:hover {
            color: #4382eb;
            text-decoration: underline;
        }

        table {
            white-space: normal;
            overflow-wrap: anywhere;
            word-break: break-all;
        }

        .table thead th,
        .table tbody td {
            border-bottom: 0px;
            font-size: .6em;
            line-height: 2.5em;
        }

        .warning {
            background-color: #fd950d;
            border-color: #fd950d;
        }

        .alert,
        .critical,
        .error,
        .emergency {
            background-color: #fd100d;
            border-color: #fd100d;
        }

        .notice,
        .info {
            background-color: #0da1fd;
            border-color: #0da1fd;
        }

        .debug {
            background-color: #6ab26d;
            border-color: #6ab26d;
        }

        .text-left {
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="row">
        <nav class="col-md-2 d-md-block bg-light sidebar">
            <div class="pt-1">
                <h6 class="mt-4 mb-1">
                    <span class="font-purple"><?php echo config('log.viewer.title') ?></span>
                </h6>
                <ul class="nav flex-column mt-3">
                    <?php foreach ($paths as $path) { ?>
                        <li class="nav-item <?php echo $path['active'] ? 'active' : ''; ?>">
                            <a class="nav-link" href="/logs/<?php echo $path['name']; ?>">
                                <?php echo $path['name']; ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </nav>

        <?php if (isset($active)) { ?>
            <div class="col-md-10 mb-3">
                <div class="pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><?php echo $active['name']; ?></h1>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="5%" scope="col">-</th>
                                <th width="5%" scope="col">Time</th>
                                <th width="5%" scope="col">Chanel</th>
                                <th width="5%" scope="col">Level</th>
                                <th width="15%" scope="col">Message</th>
                                <th width="65%" scope="col">Content</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lines as $key => $match) { ?>
                                <tr>
                                    <td>
                                        <?php echo ($key + 1); ?>
                                    </td>
                                    <td>
                                        <?php echo substr($match['time'], 11, 8); ?>
                                    </td>
                                    <td>
                                        <?php echo $match['chanel']; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo strtolower($match['level']); ?>">
                                            <?php echo $match['level']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php echo $match['message']; ?>
                                    </td>
                                    <td class="text-left">
                                        <?php echo $match['content']; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
    </div>
</body>

</html>