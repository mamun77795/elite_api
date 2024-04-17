
    <aside class="left-side sidebar-offcanvas">
        <section class="sidebar ">
            <div class="page-sidebar  sidebar-nav">
                <div class="nav_icons">
                    </br>
                </div>
                <div class="clearfix"></div>
                <!-- BEGIN SIDEBAR MENU -->
                <ul id="menu" class="page-sidebar-menu">

                    <!-- <li>
                        <a href="{{ url('shifts')}}">
                            <i class="fa fa-clock-o"></i>
                            <span class="title">SHIFT</span>
                        </a>
                    </li> -->
                    @role(['admin','viewer','developer'])
                    <li class="active">
                        <a href="{{ url('home')}}">
                            <i class="fa fa-home"></i>
                            <span class="title">DASHBOARD</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('userlists')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">USERS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('hierarchys')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">Hierarchys</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('vaccinations')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">Vaccinations</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('aci_dealers')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">aci dealers</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('dealer_visits')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">dealer visit</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('feedbacks')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">feedbacks</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('aci_farms')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">aci farms</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('companies')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">COMPANY</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('company_visits')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">COMPANY VISIT</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('enduser_designations')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">USER INFO</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('enduser_salaries')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">USER SALARY</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('forms')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">FORMS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('packages')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">PACKAGE</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('user_leave_applications')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">User Leave Application</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('client_leaves')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">Client Leave</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('comments')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">COMMENTS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('problem_submissions')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">PROBLEM SUBMISSION</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('report_images')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">REPORT IMAGES</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('sales_orders')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">SALES ORDERS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('dealer_sales_details')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">DEALER SALES INFO</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('advance_expenses_type2')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">ADVANCE EXPENSE</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('push_notifications')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">PUSH NOTIFICATIONS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('time_tables')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">TIME TABLES</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('calendar_events')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">CALENDER EVENTS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('attendancetype2')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">ADVANCE ATTENDANCES</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('advance_subagents')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">ADVANCE SUB AGENT</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('advance_farms')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">ADVANCE FARM</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('phone_changes')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">PHONE CHANGES</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('features')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">FEATURES</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('product_types')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">PRODUCT TYPE</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('company_props')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">Company Property</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('advance_tasks')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">ADVANCE TASKS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('subagents')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">SUB AGENTS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('farms')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">FARMS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('role_supervisors')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">ROLE SUPERVISORS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('activity_suggestions')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">ACTIVITY SUGGESTIONS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('activity_suggestion_locations')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">NOTIFICATION LOCATIONS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('advance_locations')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">ADVANCED NOTIFICATION LOCATIONS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('carts')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">CARTS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('logs')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">LOGS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('app_features')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">APP FEATURES</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('monthly_sales_targets')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">MONTHLY SALES TARGETS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('products')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">PRODUCTS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('payment_types')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">PAYMENT TYPES</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">BASIC INFOS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('feeds')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">FEEDS</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('feed_mills')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">FEED MILLS</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('breeds')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">BREEDS</span>
                              </a>
                          </li>
                          </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">DEVICES</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('device_admin')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">ADMINS</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('device_user')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">USERS</span>
                              </a>
                          </li>
                          </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">CLIENTS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('clients')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">LISTS</span>
                              </a>
                          </li>
                          <li>
                              <a href="#">
                                  <i class="fa fa-th"></i>
                                  <span class="title">INVITATIONS</span>
                                  <span class="fa arrow"></span>
                              </a>
                              <ul class="sub-menu">
                                <li>
                                    <a href="{{ url('used_invitations')}}">
                                        <i class="fa fa-building-o"></i>
                                        <span class="title">USED INVITATIONS</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('unused_invitations')}}">
                                        <i class="fa fa-building-o"></i>
                                        <span class="title">UNUSED INVITATIONS</span>
                                    </a>
                                </li>
                                </ul>
                          </li>
                           <li>
                              <a href="{{ url('supervisors')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">SUPERVISORS</span>
                              </a>
                          </li>
                          <li>
                             <a href="{{ url('dealers')}}">
                                 <i class="fa fa-building-o"></i>
                                 <span class="title">DEALERS</span>
                             </a>
                         </li>
                         <li>
                            <a href="{{ url('dealer_hits')}}">
                                <i class="fa fa-building-o"></i>
                                <span class="title">DEALER HITS</span>
                            </a>
                        </li>
                        <li>
                           <a href="{{ url('advance_dealer_hits')}}">
                               <i class="fa fa-building-o"></i>
                               <span class="title">ADVANCE DEALER HITS</span>
                           </a>
                       </li>
                       <li>
                          <a href="{{ url('outlet_hits')}}">
                              <i class="fa fa-building-o"></i>
                              <span class="title">OUTLET HITS</span>
                          </a>
                      </li>
                        <li>
                           <a href="{{ url('dealer_endusers')}}">
                               <i class="fa fa-building-o"></i>
                               <span class="title">DEALER ENDUSERS</span>
                           </a>
                       </li>
                         <li>
                              <a href="{{ url('agents')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">AGENTS</span>
                              </a>
                          </li>
                          <li>
                               <a href="{{ url('layer_farms')}}">
                                   <i class="fa fa-building-o"></i>
                                   <span class="title">LAYER FARMS</span>
                               </a>
                           </li>
                           <li>
                                <a href="{{ url('broiler_farms')}}">
                                    <i class="fa fa-building-o"></i>
                                    <span class="title">BROILER FARMS</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('client_server_keys')}}">
                                    <i class="fa fa-building-o"></i>
                                    <span class="title">CLIENT SERVER KEYS</span>
                                </a>
                            </li>
                          <li>
                              <a href="{{ url('payments')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">PAYMENTS</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('receipts')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">RECEIPTS</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('client_setups')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">SETUPS</span>
                              </a>
                          </li>
                          <li>
                               <a href="{{ url('version_notifications')}}">
                                   <i class="fa fa-building-o"></i>
                                   <span class="title">VERSION NOTIFICATIONS</span>
                               </a>
                           </li>

                        </ul>
                    </li>

                    @endrole

                    @role(['nourish'])
                    <li class="active">
                        <a href="{{ url('home')}}">
                            <i class="fa fa-home"></i>
                            <span class="title">DASHBOARD</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('userlists')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">USER LIST</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('photos')}}">
                            <i class="fa fa-users"></i>
                            <span class="title">PHOTOS</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">INVITATIONS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('used_invitations')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">USED INVITATIONS</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('unused_invitations')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">UNUSED INVITATIONS</span>
                              </a>
                          </li>
                          </ul>
                    </li>
                    <li>
                        <a href="{{ url('attendances')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">ATTENDANCES</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('daily_activities')}}">
                            <i class="fa fa-users"></i>
                            <span class="title">DAILY ACTIVITY</span>
                        </a>
                    </li>
                    <li>
                       <a href="{{ url('dealers')}}">
                           <i class="fa fa-building-o"></i>
                           <span class="title">DEALERS</span>
                       </a>
                   </li>
                   <li>
                      <a href="{{ url('dealer_hits')}}">
                          <i class="fa fa-building-o"></i>
                          <span class="title">DEALER HITS</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ url('subagents')}}">
                          <i class="fa fa-mobile"></i>
                          <span class="title">SUB AGENTS</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ url('farms')}}">
                          <i class="fa fa-mobile"></i>
                          <span class="title">FARMS</span>
                      </a>
                  </li>
                    <li>
                        <a href="{{ url('messages')}}">
                            <i class="fa fa-comment-o"></i>
                            <span class="title">MESSAGES</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('activity_suggestion_locations')}}">
                            <i class="fa fa-location-arrow"></i>
                            <span class="title">LOCATIONS</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">STATUSES</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('onlinestatuses')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">ONLINE STATUSES</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('offlinestatuses')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">OFFLINE STATUSES</span>
                              </a>
                          </li>
                          </ul>
                    </li>
                    <li>
                        <a href="{{ url('advance_expenses')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">EXPENSE</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('tasks')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">TASKS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('task_messages')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">TASK MESSAGES</span>
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">REPORTS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('reports')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">REPORT</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('fcr_before_sales')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">FCR(BEFORE SALES)</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('fcr_after_sales')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">FCR(AFTER SALES)</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('layer_performances')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">LAYER PERFORMANCES</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('layer_life_cycles')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">LAYER LIFE CYCLE</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('broiler_life_cycles')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">BROILER LIFE CYCLE</span>
                              </a>
                          </li>
                          </ul>
                    </li>

                    @endrole

                    @role(['getco'])
                    <li class="active">
                        <a href="{{ url('home')}}">
                            <i class="fa fa-home"></i>
                            <span class="title">DASHBOARD</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('userlists')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">USER LIST</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('photos')}}">
                            <i class="fa fa-users"></i>
                            <span class="title">PHOTOS</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">INVITATIONS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('used_invitations')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">USED INVITATIONS</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('unused_invitations')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">UNUSED INVITATIONS</span>
                              </a>
                          </li>
                          </ul>
                    </li>
                    <li>
                        <a href="{{ url('attendances')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">ATTENDANCES</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('outlets')}}">
                            <i class="fa fa-building-o"></i>
                            <span class="title">OUTLETS</span>
                        </a>
                    </li>
                    <li>
                       <a href="{{ url('outlet_hits')}}">
                           <i class="fa fa-building-o"></i>
                           <span class="title">OUTLET HITS</span>
                       </a>
                   </li>
                   <li>
                       <a href="{{ url('sales_orders')}}">
                           <i class="fa fa-mobile"></i>
                           <span class="title">SALES ORDER</span>
                       </a>
                   </li>
                    <li>
                        <a href="{{ url('tour_plans')}}">
                            <i class="fa fa-users"></i>
                            <span class="title">TOUR PLAN</span>
                        </a>
                    </li>
                    <!-- <li>
                        <a href="{{ url('archives')}}">
                            <i class="fa fa-users"></i>
                            <span class="title">ARCHIVES</span>
                        </a>
                    </li> -->
                    <li>
                        <a href="{{ url('messages')}}">
                            <i class="fa fa-comment-o"></i>
                            <span class="title">MESSAGES</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('advance_locations')}}">
                            <i class="fa fa-location-arrow"></i>
                            <span class="title">LOCATIONS</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">STATUSES</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('onlinestatuses')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">ONLINE STATUSES</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('offlinestatuses')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">OFFLINE STATUSES</span>
                              </a>
                          </li>
                          </ul>
                    </li>
                    <li>
                        <a href="{{ url('advance_expenses')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">EXPENSE</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('tasks')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">TASKS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('task_messages')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">TASK MESSAGES</span>
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">REPORTS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('reports')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">REPORT</span>
                              </a>
                          </li>
                          </ul>
                    </li>

                    @endrole

                    @role(['alpha'])
                    <li class="active">
                        <a href="{{ url('home')}}">
                            <i class="fa fa-home"></i>
                            <span class="title">DASHBOARD</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('userlists')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">USER LIST</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('photos')}}">
                            <i class="fa fa-users"></i>
                            <span class="title">PHOTOS</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">INVITATIONS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('used_invitations')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">USED INVITATIONS</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('unused_invitations')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">UNUSED INVITATIONS</span>
                              </a>
                          </li>
                          </ul>
                    </li>
                    <li>
                        <a href="{{ url('attendances')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">ATTENDANCES</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('daily_activities')}}">
                            <i class="fa fa-users"></i>
                            <span class="title">DAILY ACTIVITY</span>
                        </a>
                    </li>
                    <li>
                       <a href="{{ url('dealers')}}">
                           <i class="fa fa-building-o"></i>
                           <span class="title">DEALERS</span>
                       </a>
                   </li>
                   <li>
                      <a href="{{ url('dealer_hits')}}">
                          <i class="fa fa-building-o"></i>
                          <span class="title">DEALER HITS</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ url('subagents')}}">
                          <i class="fa fa-mobile"></i>
                          <span class="title">SUB AGENTS</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ url('farms')}}">
                          <i class="fa fa-mobile"></i>
                          <span class="title">FARMS</span>
                      </a>
                  </li>
                    <li>
                        <a href="{{ url('messages')}}">
                            <i class="fa fa-comment-o"></i>
                            <span class="title">MESSAGES</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('activity_suggestion_locations')}}">
                            <i class="fa fa-location-arrow"></i>
                            <span class="title">LOCATIONS</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">STATUSES</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('onlinestatuses')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">ONLINE STATUSES</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('offlinestatuses')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">OFFLINE STATUSES</span>
                              </a>
                          </li>
                          </ul>
                    </li>
                    <li>
                        <a href="{{ url('advance_expenses')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">EXPENSE</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('tasks')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">TASKS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('task_messages')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">TASK MESSAGES</span>
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">REPORTS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('reports')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">REPORT</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('fcr_before_sales')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">FCR(BEFORE SALES)</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('fcr_after_sales')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">FCR(AFTER SALES)</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('layer_performances')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">LAYER PERFORMANCES</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('layer_life_cycles')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">LAYER LIFE CYCLE</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('broiler_life_cycles')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">BROILER LIFE CYCLE</span>
                              </a>
                          </li>
                          </ul>
                    </li>

                    @endrole

                    @role(['client'])
                    <li class="active">
                        <a href="{{ url('home')}}">
                            <i class="fa fa-home"></i>
                            <span class="title">DASHBOARD</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('supervisors')}}">
                            <i class="fa fa-users"></i>
                            <span class="title">SUPERVISORS</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">INVITATIONS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('used_invitations')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">USED INVITATIONS</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('unused_invitations')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">UNUSED INVITATIONS</span>
                              </a>
                          </li>
                          </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">USER</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('endusers')}}">
                                  <i class="fa fa-users"></i>
                                  <span class="title">USER LISTS</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('photos')}}">
                                  <i class="fa fa-users"></i>
                                  <span class="title">USER PHOTOS</span>
                              </a>
                          </li>
                          </ul>
                    </li>
                    <li>
                        <a href="{{ url('attendancetype2')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">ATTENDANCES</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">DEALERS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                             <a href="{{ url('dealers')}}">
                                 <i class="fa fa-building-o"></i>
                                 <span class="title">DEALER LISTS</span>
                             </a>
                         </li>
                         <li>
                            <a href="{{ url('dealer_hits')}}">
                                <i class="fa fa-building-o"></i>
                                <span class="title">DEALER HITS</span>
                            </a>
                        </li>
                          </ul>
                    </li>
                    <li>
                        <a href="{{ url('subagents')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">SUB AGENTS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('farms')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">FARMS</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('daily_activities')}}">
                            <i class="fa fa-users"></i>
                            <span class="title">DAILY ACTIVITIES</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('advance_locations')}}">
                            <i class="fa fa-location-arrow"></i>
                            <span class="title">LOCATIONS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('advance_expenses_type2')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">EXPENSES</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">TASKS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('tasks')}}">
                                  <i class="fa fa-dot-circle-o"></i>
                                  <span class="title">TASK LISTS</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('task_messages')}}">
                                  <i class="fa fa-dot-circle-o"></i>
                                  <span class="title">TASK MESSAGES</span>
                              </a>
                          </li>
                          </ul>
                    </li>
                    <li>
                        <a href="{{ url('messages')}}">
                            <i class="fa fa-comment-o"></i>
                            <span class="title">MESSAGES</span>
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">STATUSES</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('onlinestatuses')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">ONLINE STATUSES</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('offlinestatuses')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">OFFLINE STATUSES</span>
                              </a>
                          </li>
                          </ul>
                    </li>



                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">REPORTS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('reports')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">REPORT</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('fcr_before_sales')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">FCR(BEFORE SALES)</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('fcr_after_sales')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">FCR(AFTER SALES)</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('layer_performances')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">LAYER PERFORMANCES</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('layer_life_cycles')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">LAYER LIFE CYCLE</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('broiler_life_cycles')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">BROILER LIFE CYCLE</span>
                              </a>
                          </li>
                          </ul>
                    </li>

                    @endrole

                    @role(['amrit'])
                    <li class="active">
                        <a href="{{ url('home')}}">
                            <i class="fa fa-home"></i>
                            <span class="title">DASHBOARD</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">INVITATIONS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('used_invitations')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">USED INVITATIONS</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('unused_invitations')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">UNUSED INVITATIONS</span>
                              </a>
                          </li>
                          </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">USER</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('userlists')}}">
                                  <i class="fa fa-users"></i>
                                  <span class="title">USER LISTS</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('photos')}}">
                                  <i class="fa fa-users"></i>
                                  <span class="title">USER PHOTOS</span>
                              </a>
                          </li>
                          </ul>
                    </li>
                    <li>
                        <a href="{{ url('attendances')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">ATTENDANCES</span>
                        </a>
                    </li>
                    <li>
                       <a href="{{ url('advance_dealers')}}">
                           <i class="fa fa-building-o"></i>
                           <span class="title">DEALERS</span>
                       </a>
                   </li>
                   <li>
                       <a href="{{ url('advance_subagents')}}">
                           <i class="fa fa-mobile"></i>
                           <span class="title">SUB AGENTS</span>
                       </a>
                   </li>
                   <li>
                       <a href="{{ url('advance_farms')}}">
                           <i class="fa fa-mobile"></i>
                           <span class="title">FARMS</span>
                       </a>
                   </li>

                   <li>
                       <a href="{{ url('daily_activities')}}">
                           <i class="fa fa-users"></i>
                           <span class="title">DAILY ACTIVITIES</span>
                       </a>
                   </li>
                   <li>
                       <a href="{{ url('products')}}">
                           <i class="fa fa-mobile"></i>
                           <span class="title">PRODUCTS</span>
                       </a>
                   </li>
                  <li>
                      <a href="{{ url('carts')}}">
                          <i class="fa fa-mobile"></i>
                          <span class="title">SALES ORDER</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ url('advance_expenses')}}">
                          <i class="fa fa-dot-circle-o"></i>
                          <span class="title">EXPENSES</span>
                      </a>
                  </li>
                    <li>
                        <a href="{{ url('messages')}}">
                            <i class="fa fa-comment-o"></i>
                            <span class="title">MESSAGES</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">REPORTS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('farm_visit_layers')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">FARM VISIT LAYER</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('farm_visit_broilers')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">FARM VISIT BROILER</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('farm_visit_fishs')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">FARM VISIT FISH</span>
                              </a>
                          </li>
                          </ul>
                    </li>

                    @endrole

                    @role(['bdthai'])
                    <li class="active">
                        <a href="{{ url('home')}}">
                            <i class="fa fa-home"></i>
                            <span class="title">DASHBOARD</span>
                        </a>
                    </li>
                    <li>
                       <a href="{{ url('supervisors')}}">
                           <i class="fa fa-building-o"></i>
                           <span class="title">SUPERVISORS</span>
                       </a>
                   </li>
                    <li>
                        <a href="{{ url('userlists')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">USER LIST</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('photos')}}">
                            <i class="fa fa-users"></i>
                            <span class="title">PHOTOS</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">INVITATIONS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('used_invitations')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">USED INVITATIONS</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('unused_invitations')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">UNUSED INVITATIONS</span>
                              </a>
                          </li>
                          </ul>
                    </li>
                    <li>
                        <a href="{{ url('attendances')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">ATTENDANCES</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('outlets')}}">
                            <i class="fa fa-building-o"></i>
                            <span class="title">OUTLETS</span>
                        </a>
                    </li>
                    <li>
                       <a href="{{ url('outlet_hits')}}">
                           <i class="fa fa-building-o"></i>
                           <span class="title">OUTLET HITS</span>
                       </a>
                   </li>
                   <li>
                       <a href="{{ url('monthly_sales_targets')}}">
                           <i class="fa fa-mobile"></i>
                           <span class="title">MONTHLY SALES TARGETS</span>
                       </a>
                   </li>
                   <li>
                       <a href="{{ url('sales_orders')}}">
                           <i class="fa fa-mobile"></i>
                           <span class="title">SALES ORDER</span>
                       </a>
                   </li>
                    <li>
                        <a href="{{ url('tour_plans')}}">
                            <i class="fa fa-users"></i>
                            <span class="title">TOUR PLAN</span>
                        </a>
                    </li>
                    <!-- <li>
                        <a href="{{ url('archives')}}">
                            <i class="fa fa-users"></i>
                            <span class="title">ARCHIVES</span>
                        </a>
                    </li> -->
                    <li>
                        <a href="{{ url('messages')}}">
                            <i class="fa fa-comment-o"></i>
                            <span class="title">MESSAGES</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('advance_locations')}}">
                            <i class="fa fa-location-arrow"></i>
                            <span class="title">NEW LOCATIONS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('activity_suggestion_locations')}}">
                            <i class="fa fa-location-arrow"></i>
                            <span class="title">LOCATIONS</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">STATUSES</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('onlinestatuses')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">ONLINE STATUSES</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('offlinestatuses')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">OFFLINE STATUSES</span>
                              </a>
                          </li>
                          </ul>
                    </li>
                    <li>
                        <a href="{{ url('advance_expenses')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">EXPENSE</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('tasks')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">TASKS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('task_messages')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">TASK MESSAGES</span>
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">REPORTS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('reports')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">REPORT</span>
                              </a>
                          </li>
                          </ul>
                    </li>

                    @endrole

                    @role(['daimond'])
                    <li class="active">
                        <a href="{{ url('home')}}">
                            <i class="fa fa-home"></i>
                            <span class="title">DASHBOARD</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('userlists')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">USER LIST</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('photos')}}">
                            <i class="fa fa-users"></i>
                            <span class="title">PHOTOS</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">INVITATIONS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('used_invitations')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">USED INVITATIONS</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('unused_invitations')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">UNUSED INVITATIONS</span>
                              </a>
                          </li>
                          </ul>
                    </li>
                    <li>
                        <a href="{{ url('attendances')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">ATTENDANCES</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('daily_activities')}}">
                            <i class="fa fa-users"></i>
                            <span class="title">DAILY ACTIVITY</span>
                        </a>
                    </li>
                    <li>
                       <a href="{{ url('dealers')}}">
                           <i class="fa fa-building-o"></i>
                           <span class="title">DEALERS</span>
                       </a>
                   </li>
                   <li>
                      <a href="{{ url('dealer_hits')}}">
                          <i class="fa fa-building-o"></i>
                          <span class="title">DEALER HITS</span>
                      </a>
                  </li>

                    <!-- <li>
                        <a href="{{ url('archives')}}">
                            <i class="fa fa-users"></i>
                            <span class="title">ARCHIVES</span>
                        </a>
                    </li> -->
                    <li>
                        <a href="{{ url('messages')}}">
                            <i class="fa fa-comment-o"></i>
                            <span class="title">MESSAGES</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('activity_suggestion_locations')}}">
                            <i class="fa fa-location-arrow"></i>
                            <span class="title">LOCATIONS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('advance_tour_plans')}}">
                            <i class="fa fa-location-arrow"></i>
                            <span class="title">ADVANCE TOUR PLAN</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">STATUSES</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('onlinestatuses')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">ONLINE STATUSES</span>
                              </a>
                          </li>
                          <li>
                              <a href="{{ url('offlinestatuses')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">OFFLINE STATUSES</span>
                              </a>
                          </li>
                          </ul>
                    </li>
                    <li>
                        <a href="{{ url('advance_expenses')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">EXPENSE</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('tasks')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">TASKS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('task_messages')}}">
                            <i class="fa fa-dot-circle-o"></i>
                            <span class="title">TASK MESSAGES</span>
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">REPORTS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li>
                              <a href="{{ url('reports')}}">
                                  <i class="fa fa-building-o"></i>
                                  <span class="title">REPORT</span>
                              </a>
                          </li>
                          </ul>
                    </li>

                    @endrole

                    @role(['admin','viewer','developer'])
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span class="title">END USERS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a href="{{ url('endusers')}}">
                                    <i class="fa fa-users"></i>
                                    <span class="title">USER LISTS</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('photos')}}">
                                    <i class="fa fa-users"></i>
                                    <span class="title">PHOTOS</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('advance_tour_plans')}}">
                                    <i class="fa fa-users"></i>
                                    <span class="title">ADVANCE TOUR PLAN</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('actual_tour_plans')}}">
                                    <i class="fa fa-users"></i>
                                    <span class="title">ACTUAL TOUR PLAN</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('tour_plans')}}">
                                    <i class="fa fa-users"></i>
                                    <span class="title">TOUR PLAN</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('advance_payments')}}">
                                    <i class="fa fa-users"></i>
                                    <span class="title">ADVANCE PAYMENT</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('remainders')}}">
                                    <i class="fa fa-users"></i>
                                    <span class="title">REMAINDERS</span>
                                </a>
                            </li>
                            <!-- <li>
                                <a href="{{ url('archives')}}">
                                    <i class="fa fa-users"></i>
                                    <span class="title">ARCHIVES</span>
                                </a>
                            </li> -->
                            <li>
                                <a href="#">
                                    <i class="fa fa-th"></i>
                                    <span class="title">DAILY ACTIVITIES</span>
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                  <li>
                                      <a href="{{ url('daily_activities')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">DAILY ACTIVITY</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ url('visiting_farmers')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">VISITED FARMERS</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ url('visiting_agents')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">VISITED AGENTS</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ url('visiting_sub_agents')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">VISITED SUB AGENTS</span>
                                      </a>
                                  </li>
                                  </ul>
                            </li>

                            <li>
                                <a href="{{ url('daily_reports')}}">
                                    <i class="fa fa-building-o"></i>
                                    <span class="title">DAILY REPORTS</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ url('outlets')}}">
                                    <i class="fa fa-building-o"></i>
                                    <span class="title">OUTLETS</span>
                                </a>
                            </li>

                            <li>
                                <a href="#">
                                    <i class="fa fa-th"></i>
                                    <span class="title">ADVANCE DEALERS</span>
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                  <li>
                                      <a href="{{ url('zones')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">ZONE</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ url('regions')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">REGION</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ url('divisions')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">DIVISION</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ url('districts')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">DISTRICT</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ url('upozilas')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">UPOZILA</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ url('advance_dealers')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">DEALER</span>
                                      </a>
                                  </li>
                                  </ul>
                            </li>

                            <li>
                                <a href="{{ url('messages')}}">
                                    <i class="fa fa-comment-o"></i>
                                    <span class="title">MESSAGES</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('locations')}}">
                                    <i class="fa fa-location-arrow"></i>
                                    <span class="title">LOCATIONS</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-th"></i>
                                    <span class="title">STATUSES</span>
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                  <li>
                                      <a href="{{ url('onlinestatuses')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">ONLINE STATUSES</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ url('offlinestatuses')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">OFFLINE STATUSES</span>
                                      </a>
                                  </li>
                                  </ul>
                            </li>
                            <li>
                                <a href="{{ url('advance_expenses')}}">
                                    <i class="fa fa-dot-circle-o"></i>
                                    <span class="title">ADVANCE EXPENSES</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('conveyances')}}">
                                    <i class="fa fa-dot-circle-o"></i>
                                    <span class="title">CONVEYANCES</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('visit_details')}}">
                                    <i class="fa fa-dot-circle-o"></i>
                                    <span class="title">EXPENSES</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('tasks')}}">
                                    <i class="fa fa-dot-circle-o"></i>
                                    <span class="title">TASKS</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('task_messages')}}">
                                    <i class="fa fa-dot-circle-o"></i>
                                    <span class="title">TASK MESSAGES</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('attendances')}}">
                                    <i class="fa fa-dot-circle-o"></i>
                                    <span class="title">ATTENDANCES</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-th"></i>
                                    <span class="title">REPORTS</span>
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                  <li>
                                      <a href="{{ url('reports')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">REPORT</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ url('fcr_before_sales')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">FCR(BEFORE SALES)</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ url('fcr_after_sales')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">FCR(AFTER SALES)</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ url('fcr_reports')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">FCR</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ url('layer_performances')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">LAYER PERFORMANCES</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ url('layer_life_cycles')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">LAYER LIFE CYCLE</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ url('broiler_life_cycles')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">BROILER LIFE CYCLE</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ url('farm_visit_layers')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">FARM VISIT LAYER</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ url('farm_visit_broilers')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">FARM VISIT BROILER</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ url('farm_visit_fishs')}}">
                                          <i class="fa fa-building-o"></i>
                                          <span class="title">FARM VISIT FISH</span>
                                      </a>
                                  </li>
                                  </ul>
                            </li>
                        </ul>
                    </li>
                    @endrole
                    {{--@role(['admin','company','viewer'])--}}

                    <!-- <li>
                        <a href="{{ url('designations')}}">
                            <i class="fa fa-briefcase"></i>
                            <span class="title">DESIGNATIONS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('employee_shifts')}}">
                            <i class="fa fa-clock-o"></i>
                            <span class="title">EMPLOYEES SHIFT</span>
                        </a>
                    </li> -->
                    {{--@endrole--}}



                    <!-- <li>
                        <a href="{{ url('payments')}}">
                            <i class="fa fa-money"></i>
                            <span class="title">PAYMENTS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('paymentmethods')}}">
                            <i class="fa fa-credit-card"></i>
                            <span class="title">PAYMENT METHODS</span>
                        </a>
                    </li> -->

                    <!-- <li>
                        <a href="{{ url('attendances')}}">
                            <i class="fa fa-mobile"></i>
                            <span class="title">ATTENDANCES</span>
                        </a>
                    </li> -->

                    <!-- <li>
                        <a href="{{ url('calendars')}}">
                            <i class="fa fa-calendar-o"></i>
                            <span class="title">EVENTS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('calendar')}}">
                            <i class="fa fa-calendar"></i>
                            <span class="title">CALENDAR</span>
                        </a>
                    </li> -->
                    @role(['admin'])
                    <li>
                        <a href="{{ url('users')}}">
                            <i class="fa fa-bar-chart"></i>
                            <span class="title">ADMINS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('activities')}}">
                            <i class="fa fa-bar-chart"></i>
                            <span class="title">LOGIN HISTORIES</span>
                        </a>
                    </li>
                    <!-- <li>
                        <a href="{{ url('packages')}}">
                            <i class="fa fa-bars"></i>
                            <span class="title">PACKAGE</span>
                        </a>
                    </li> -->
                    @endrole


                </ul>
            </div>
        </section>
    </aside>
