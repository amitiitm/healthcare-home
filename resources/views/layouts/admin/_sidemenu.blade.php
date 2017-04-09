<div class="side-menu sidebar-inverse-remove">

    <nav class="navbar navbar-default" role="navigation">
        <div class="side-menu-container">
            <div class="navbar-header">
                <a class="navbar-brand" href="<% url() %>">
                    <div class="icon fa fa-paper-plane"></div>
                    <div class="title">Pramaticare</div>
                </a>
                <button type="button" class="navbar-expand-toggle pull-right visible-xs">
                    <i class="fa fa-times icon"></i>
                </button>
            </div>
            <ul class="nav navbar-nav">

                <?php if ($authObject->isAdmin) { ?>
                    <li <?php
                    if (!isset($menuSelected) || $menuSelected == "dashboard") {
                        echo "class='active'";
                    }
                    ?>>
                        <a href="<% url('admin/dashboard') %>">
                            <span class="icon fa fa-cube"></span><span class="title">Dashboard</span>
                        </a>
                    </li>
            <?php } ?>

                <li <?php if (!isset($menuSelected) || $menuSelected == "leads") {
    echo "class='active'";
} ?>>
                    <a href="<% url('admin/leads') %>">
                        <span class="icon fa fa-tachometer"></span><span class="title">Leads</span>
                    </a>
                </li>

                <li <?php
if (!isset($menuSelected) || $menuSelected == "orders") {
    echo "class='active'";
}
?>>
                    <a href="<% url('admin/orders') %>">
                        <span class="icon fa fa-tachometer"></span><span class="title">Orders</span>
                    </a>
                </li>

                <li <?php
                if (!isset($menuSelected) || $menuSelected == "invoices") {
                    echo "class='active'";
                }
                ?>>
                    <a href="<% url('admin/invoices') %>">
                        <span class="icon fa fa-arrows"></span><span class="title">Invoices</span>
                    </a>
                </li>

                <li <?php
                if (!isset($menuSelected) || $menuSelected == "assigned_invoices") {
                    echo "class='active'";
                }
                ?>>
                    <a href="<% url('admin/assigned_invoices') %>">
                        <span class="icon fa fa-arrows"></span><span class="title">Assigned Invoices</span>
                    </a>
                </li>
                
                <li <?php
                if (!isset($menuSelected) || $menuSelected == "reports") {
                    echo "class='active'";
                }
                ?>>
                    <a href="<% url('reports/budget_movement') %>">
                        <span class="icon fa fa-arrows"></span><span class="title">Budget Movement</span>
                    </a>
                    <ul style="height: 0px;" class="nav nav-third-level collapse">
                        <li>
                            <a href="<% url('reports/budget_movement') %>">Budget Movement</a>
                        </li>
                    </ul>
                </li>
                
                <li <?php
                if (!isset($menuSelected) || $menuSelected == "reports") {
                    echo "class='active'";
                }
                ?>>
                    <a href="<% url('reports/daily_collection') %>">
                        <span class="icon fa fa-arrows"></span><span class="title">Daily Collection</span>
                    </a>
                </li>
                
                <li <?php
                if (!isset($menuSelected) || $menuSelected == "reports") {
                    echo "class='active'";
                }
                ?>>
                    <a href="<% url('reports/field_executive_collection') %>">
                        <span class="icon fa fa-arrows"></span><span class="title">Field Exec Collection</span>
                    </a>
                </li>

                <li <?php
                if (!isset($menuSelected) || $menuSelected == "reports") {
                    echo "class='active'";
                }
                ?>>
                    <a href="<% url('reports/total_collection') %>">
                        <span class="icon fa fa-arrows"></span><span class="title">Total Collection</span>
                    </a>
                </li>
                
                <li <?php
                if (!isset($menuSelected) || $menuSelected == "cg_tracking_report") {
                    echo "class='active'";
                }
                ?>>
                    <a href="<% url('reports/cg_tracking') %>">
                        <span class="icon fa fa-arrows"></span><span class="title">CG Tracking Report</span>
                    </a>
                </li>
                
                <li <?php
                if (!isset($menuSelected) || $menuSelected == "cg_attendance_report") {
                    echo "class='active'";
                }
                ?>>
                    <a href="<% url('reports/cg_attendance') %>">
                        <span class="icon fa fa-arrows"></span><span class="title">CG Attendance Report</span>
                    </a>
                </li>
                
                <?php
                if ($authObject->isAdmin) {
                    ?>
                    <li <?php
                    if (isset($menuSelected) && $menuSelected == "users") {
                        echo "class='active'";
                    }
                    ?>>
                        <?php } ?>

                    <li <?php if(!isset($menuSelected) || $menuSelected == "leads"){ echo "class='active'" ; } ?>>
                        <a href="<% url('admin/leads') %>">
                            <span class="icon fa fa-tachometer"></span><span class="title">Leads</span>
                        </a>
                    </li>

                    <?php
                    if($authObject->isAdmin){
                    ?>
                        <li <?php if(isset($menuSelected) && $menuSelected == "users"){ echo "class='active'" ; } ?>>
                        <a href="<% url('admin/users') %>">
                            <span class="icon fa fa-users"></span><span class="title">User Management</span>
                        </a>
                    </li>
                <?php } ?>

                <li <?php if(isset($menuSelected) && $menuSelected == "enquiries"){ echo "class='active'" ; } ?>>
                    <a href="<% url('admin/enquiries1') %>">
                        <span class="icon fa fa-users"></span><span class="title">Followup</span>
                    </a>
                </li>
                
                <li <?php if(isset($menuSelected) && $menuSelected == "feedbacks"){ echo "class='active'" ; } ?>>
                    <a href="<% url('admin/lead/feedback') %>">
                        <span class="icon fa fa-users"></span><span class="title">Feedback</span>
                    </a>
                </li>
                    
                    
                <?php
                if ($authObject) {
                    ?><li <?php
                    if (isset($menuSelected) && $menuSelected == "caregiver") {
                        echo "class='active'";
                    }
                    ?>>
                        <a href="<% url('admin/caregivers') %>">
                            <span class="icon fa fa-user"></span><span class="title">Caregiver Management</span>
                        </a>
                    </li>
                    <?php } ?>
                    
                <li <?php if(isset($menuSelected) && $menuSelected == "caregiver_auto_attendance"){ echo "class='active'" ; } ?>>
                    <a href="<% url('admin/caregiver/auto_attendance') %>">
                        <span class="icon fa fa-users"></span><span class="title">CG Auto Attendance</span>
                    </a>
                </li>

                    <?php
                    if($authObject->isAdmin){
                    ?><li <?php if(isset($menuSelected) && $menuSelected == "employees"){ echo "class='active'" ; } ?>>
                    <a href="<% url('admin/employees') %>">
                        <span class="icon fa fa-user"></span><span class="title">Employee Management</span>
                    </a>
                    </li>
                    <?php } ?>

                    <?php //if($authObject->isAdmin || $authObject->isAuthorizedForTracking){ ?>
                        <li <?php if(isset($menuSelected) && $menuSelected == "employeeTracking"){ echo "class='active'" ; } ?>>
                            <a href="<% url('admin/employee/tracker') %>">
                                <span class="icon fa fa-user"></span><span class="title">Employee Tracking</span>
                            </a>
                        </li>
                    <?php //} ?>

                    <li <?php if(isset($menuSelected) && $menuSelected == "complaints"){ echo "class='active'" ; } ?>>
                        <a href="<% url('admin/complaints') %>">
                            <span class="icon fa fa-comments-o"></span><span class="title">Complaints</span>
                        </a>
                    </li>

                    <?php if($authObject->isAdmin){ ?>
                    <li <?php if(isset($menuSelected) && $menuSelected == "complaints_resolution_groups"){ echo "class='active'" ; } ?>>
                        <a href="<% url('admin/complaints_resolution_groups') %>">
                            <span class="icon fa fa-users"></span><span class="title">Complaints Resolution Groups</span>
                        </a>
                    </li>
                    <?php } ?>

                    <li <?php if(isset($menuSelected) && $menuSelected == "replacement_requests"){ echo "class='active'" ; } ?>>
                        <a href="<% url('admin/replacement_requests') %>">
                            <span class="icon fa fa-retweet"></span><span class="title">Replacement Requests</span>
                        </a>
                    </li>

                    <?php
                    if($authObject->isAdmin){
                    ?>

                <?php } ?><?php
                if ($authObject->isAdmin) {
                    ?>
                    <li <?php
                    if (isset($menuSelected) && $menuSelected == "reports") {
                        echo "class='active'";
                    }
                    ?>>
                        <a href="<% url('report/active/projects') %>">
                            <span class="icon fa fa-list"></span><span class="title">Active Projects</span>
                        </a>
                    </li>

                    <?php } ?>
                    
                    <li <?php if(isset($menuSelected) && $menuSelected == "attendancereports"){ echo "class='active'" ; } ?>>
                        <a href="<% url('report/vendor/attendance') %>">
                            <span class="icon fa fa-list"></span><span class="title">Attendance Report</span>
                        </a>
                    </li>

                    <li <?php if(isset($menuSelected) && $menuSelected == "salaryreport"){ echo "class='active'" ; } ?>>
                        <a href="<% url('report/salary') %>">
                            <span class="icon fa fa-money"></span><span class="title">Salary Reports</span>
                        </a>
                    </li>



                <!--
                <li <?php
if (isset($menuSelected) && $menuSelected == "field") {
    echo "class='active'";
}
?>>
                    <a href="<% url('admin/field') %>">
                        <span class="icon fa fa-arrows"></span><span class="title">Field Management</span>
                    </a>
                </li>
                <li <?php
if (isset($menuSelected) && $menuSelected == "operations") {
    echo "class='active'";
}
?>>
                    <a href="<% url('admin/operations') %>">
                        <span class="icon fa fa-tasks"></span><span class="title">Operations</span>
                    </a>
                </li>-->
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>
</div>
