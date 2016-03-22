
	<div class="page-sidebar-wrapper">
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->
			<ul class="page-sidebar-menu" data-auto-scroll="false" data-slide-speed="200">
				<!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
				<li class="sidebar-toggler-wrapper">
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler">
					</div>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				</li>
				@if(hasPrivilege('pm_sales','view'))
				<li id="pm_sales">
					<a href="javascript:;">
						<i class="fa fa-folder-open"></i>
						<span class="title">PM & Sales</span>
						<span class="selected"></span>
						<span class="arrow"></span>
					</a>
					<ul class="sub-menu">
						@if(hasPrivilege('pm_sales_master','view'))
						<li id="pm_sales_master">
							<a href="javascript:;">
								<i class="fa fa-folder-open"></i>  
								<span class="title">Master</span>
								<span class="arrow"></span>
							</a>
							<ul class="sub-menu">
								@if(hasPrivilege('customer_category','view'))
								<li id="customer_category">
									<a href="{{ URL::to('/') }}/customer_category"><i class="fa fa-folder"></i> Customer Category</a>
								</li>
								@endif
								@if(hasPrivilege('customer','view'))
								<li id="customer">
									<a href="{{ URL::to('/') }}/customer"><i class="fa fa-folder"></i> Customer</a>
								</li>
								@endif
								@if(hasPrivilege('project_category','view'))
								<li id="project_category">
									<a href="{{ URL::to('/') }}/project_category"><i class="fa fa-folder"></i> Project Category</a>
								</li>
								@endif
								@if(hasPrivilege('project','view'))
								<li id="project">
									<a href="{{ URL::to('/') }}/project"><i class="fa fa-folder"></i> Project</a>
								</li>
								@endif
							</ul>
						</li>
						@endif
						@if(hasPrivilege('open_project','view'))
						<li id="open_project">
							<a href="{{ URL::to('/') }}/open_project"><i class="fa fa-folder"></i> Open Project</a>
						</li>
						@endif
						@if(hasPrivilege('sales','view'))
						<li id="sales">
							<a href="javascript:;">
								<i class="fa fa-folder-open"></i>  
								<span class="title">Sales</span>
								<span class="arrow"></span>
							</a>
							<ul class="sub-menu">
								@if(hasPrivilege('sales_invoice','view'))
								<li id="sales_invoice">
									<a href="{{ URL::to('/') }}/sales/invoice"><i class="fa fa-folder"></i> Invoice & Tax</a>
								</li>
								@endif
							</ul>
						</li>
					@endif
					</ul>
				</li>
				@endif
				@if(hasPrivilege('procurement','view'))
				<li id="procurement">
					<a href="javascript:;">
					<i class="fa fa-folder-open"></i>
					<span class="title">Procurement</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						@if(hasPrivilege('procurement_master','view'))
						<li id="procurement_master">
							<a href="javascript:;">
								<i class="fa fa-folder-open"></i>  
								<span class="title">Master</span>
								<span class="arrow"></span>
							</a>
							<ul class="sub-menu">
								@if(hasPrivilege('vendor_category','view'))
								<li id="vendor_category">
									<a href="{{ URL::to('/') }}/vendor_category"><i class="fa fa-folder"></i> Vendor Category</a>
								</li>
								@endif
								@if(hasPrivilege('vendor','view'))
								<li id="vendor">
									<a href="{{ URL::to('/') }}/vendor2"><i class="fa fa-folder"></i> Vendor</a>
								</li>
								@endif
							</ul>
						</li>
						@endif
						@if(hasPrivilege('purchase_request','view'))
						<li id="purchase_request">
							<a href="{{ URL::to('/') }}/purchase/request">
							<i class="fa fa-folder"></i> Purchase Request</a>
						</li>
						@endif
						@if(hasPrivilege('purchase_order','view'))
						<li id="purchase_order">
							<a href="{{ URL::to('/') }}/purchase/order">
							<i class="fa fa-folder"></i> Purchase Order</a>
						</li>
						@endif
						@if(hasPrivilege('purchase_invoice','view'))
						<li id="purchase_invoice">
							<a href="{{ URL::to('/') }}/purchase/invoice">
							<i class="fa fa-folder"></i> Purchase Invoice</a>
						</li>
						@endif
					</ul>
				</li>
				@endif
				@if(hasPrivilege('inventory','view'))
				<li id="inventory">
					<a href="javascript:;">
						<i class="fa fa-folder-open"></i>
						<span class="title">Inventory</span>
						<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						@if(hasPrivilege('inventory_master','view'))
						<li id="inventory_master">
							<a href="javascript:;">
								<i class="fa fa-folder-open"></i>  
								<span class="title">Master</span>
								<span class="arrow"></span>
							</a>
							<ul class="sub-menu">
								@if(hasPrivilege('warehouse','view'))
								<li id="warehouse">
									<a href="{{ URL::to('/') }}/warehouse"><i class="fa fa-folder"></i> Warehouse</a>
								</li>
								@endif
								@if(hasPrivilege('item_category','view'))
								<li id="item_category">
									<a href="{{ URL::to('/') }}/item_category"><i class="fa fa-folder"></i> Item Category</a>
								</li>
								@endif
								@if(hasPrivilege('item_brand','view'))
								<li id="item_brand">
									<a href="{{ URL::to('/') }}/item_brand"><i class="fa fa-folder"></i> Item Brand</a>
								</li>
								@endif
								@if(hasPrivilege('item','view'))
								<li id="item">
									<a href="{{ URL::to('/') }}/item"><i class="fa fa-folder"></i> Item</a>
								</li>
								@endif
							</ul>
						</li>
						@endif
						@if(hasPrivilege('view_stock','view'))
						<li id="view_stock">
							<a href="{{ URL::to('/') }}/inventory/data"><i class="fa fa-folder"></i> View Stock</a>
						</li>
						@endif
						@if(hasPrivilege('stock_opname','view'))
						<li id="stock_opname">
							<a href="{{ URL::to('/') }}/inventory/opname"><i class="fa fa-folder"></i> Stock Opname</a>
						</li>
						@endif
						@if(hasPrivilege('stock_transfer','view'))
						<li id="stock_transfer">
							<a href="{{ URL::to('/') }}/inventory/transfer"><i class="fa fa-folder"></i> Stock Transfer</a>
						</li>
						@endif
						@if(hasPrivilege('good_receipt','view'))
						<li id="good_receipt">
							<a href="{{ URL::to('/') }}/inventory/good_receipt"><i class="fa fa-folder"></i> Good Receipt</a>
						</li>
						@endif
						@if(hasPrivilege('delivery_order','view'))
						<li id="delivery_order">
							<a href="{{ URL::to('/') }}/inventory/delivery_order"><i class="fa fa-folder"></i> Delivery Order</a>
						</li>
						@endif
						@if(hasPrivilege('stock_return','view'))
						<li id="stock_return">
							<a href="{{ URL::to('/') }}/inventory/return"><i class="fa fa-folder"></i> Stock Return</a>
						</li>
						@endif
					</ul>
				</li>
				@endif
				@if(hasPrivilege('form_request','view'))
				<li id="form_request">
					<a href="javascript:;">
						<i class="fa fa-folder-open"></i>  
						<span class="title">Form Request</span>
						<span class="arrow"></span>
					</a>
					<ul class="sub-menu">
						@if(hasPrivilege('create_request','view'))
						<li id="create_request">
							<a href="javascript:;">
								<i class="fa fa-folder-open"></i>  
								<span class="title">Create</span>
								<span class="arrow"></span>
							</a>
							<ul class="sub-menu">
								@if(hasPrivilege('request_advance','new'))
								<li id="request_advance">
									<a href="{{ URL::to('/') }}/request/advance"><i class="fa fa-folder"></i> Advance</a>
								</li>
								@endif
								@if(hasPrivilege('request_expense','new'))
								<li id="request_expense">
									<a href="{{ URL::to('/') }}/request/expense"><i class="fa fa-folder"></i> Expense</a>
								</li>
								@endif
								@if(hasPrivilege('request_overtime','new'))
								<li id="request_overtime">
									<a href="{{ URL::to('/') }}/request/overtime"><i class="fa fa-folder"></i> Overtime</a>
								</li>
								@endif
							</ul>
						</li>
						@endif
						@if(hasPrivilege('request_approval','view'))
						<li id="request_approval">
							<a href="{{ URL::to('/') }}/request/waiting_approval"><i class="fa fa-folder"></i> Waiting for Approval</a>
						</li>
						@endif
						@if(hasPrivilege('request_release','view'))
						<li id="request_release">
							<a href="{{ URL::to('/') }}/request/waiting_release"><i class="fa fa-folder"></i> Waiting for Release</a>
						</li>
						@endif
					</ul>
				</li>
				@endif
				@if(hasPrivilege('accounting','view'))
				<li id="accounting">
					<a href="javascript:;">
						<i class="fa fa-folder-open"></i>  
						<span class="title">Accounting</span>
						<span class="arrow"></span>
					</a>
					<ul class="sub-menu">
						@if(hasPrivilege('accounting_master','view'))
						<li id="accounting_master">
							<a href="javascript:;">
								<i class="fa fa-folder-open"></i>  
								<span class="title">Master</span>
								<span class="arrow"></span>
							</a>
							<ul class="sub-menu">
								@if(hasPrivilege('coa','view'))
								<li id="coa">
									<a href="{{ URL::to('/') }}/accounting/coa"><i class="fa fa-folder"></i> Chart of Account</a>
								</li>
								@endif
								@if(hasPrivilege('currency','view'))
								<li id="currency">
									<a href="{{ URL::to('/') }}/accounting/currency"><i class="fa fa-folder"></i> Currency</a>
								</li>
								@endif
							</ul>
						</li>
						@endif
						@if(hasPrivilege('accounting_treasury','view'))
						<li id="accounting_treasury">
							<a href="javascript:;">
								<i class="fa fa-folder-open"></i>  
								<span class="title">Treasury</span>
								<span class="arrow"></span>
							</a>
							<ul class="sub-menu">
								@if(hasPrivilege('cash_adjustment','new'))
								<!--<li id="cash_adjustment">
									<a href="{{ URL::to('/') }}/accounting/cash_adjustment"><i class="fa fa-folder"></i> Cash Adjustment</a>
								</li>-->
								@endif
								@if(hasPrivilege('cashbank_transfer','new'))
								<li id="cashbank_transfer">
									<a href="{{ URL::to('/') }}/accounting/cashbank_transfer"><i class="fa fa-folder"></i> Cash/Bank Transfer</a>
								</li>
								@endif
								@if(hasPrivilege('bank_recon','new'))
								<!--<li id="bank_recon">
									<a href="{{ URL::to('/') }}/accounting/bank_recon"><i class="fa fa-folder"></i> Bank Reconciliation</a>
								</li>-->
								@endif
								@if(hasPrivilege('deposits','new'))
								<li id="deposits">
									<a href="{{ URL::to('/') }}/accounting/deposits"><i class="fa fa-folder"></i> Deposits</a>
								</li>
								@endif
								@if(hasPrivilege('payments','new'))
								<li id="payments">
									<a href="{{ URL::to('/') }}/accounting/payments"><i class="fa fa-folder"></i> Payments</a>
								</li>
								@endif
								@if(hasPrivilege('acct_receivable','view'))
								<li id="acct_receivable">
									<a href="{{ URL::to('/') }}/accounting/acct_receivable"><i class="fa fa-folder"></i> Account Receivable</a>
								</li>
								@endif
								@if(hasPrivilege('acct_payable','view'))
								<li id="acct_payable">
									<a href="{{ URL::to('/') }}/accounting/acct_payable"><i class="fa fa-folder"></i> Account Payable</a>
								</li>
								@endif
							</ul>
						</li>
						@endif
						@if(hasPrivilege('memorial_journal','new'))
						<li id="memorial_journal">
							<a href="{{ URL::to('/') }}/accounting/memorial_journal"><i class="fa fa-folder"></i> Memorial Journal</a>
						</li>
						@endif
						@if(hasPrivilege('accounting_report','view'))
						<li id="accounting_report">
							<a href="javascript:;">
								<i class="fa fa-folder-open"></i>  
								<span class="title">Report</span>
								<span class="arrow"></span>
							</a>
							<ul class="sub-menu">
								@if(hasPrivilege('general_journal','view'))
								<li id="general_journal">
									<a href="{{ URL::to('/') }}/accounting/report/general_journal"><i class="fa fa-folder"></i> General Journal</a>
								</li>
								@endif
								@if(hasPrivilege('balance_sheet','view'))
								<li id="balance_sheet">
									<a href="{{ URL::to('/') }}/accounting/report/balance_sheet"><i class="fa fa-folder"></i> Balance Sheet</a>
								</li>
								@endif
								@if(hasPrivilege('trial_balance','view'))
								<li id="trial_balance">
									<a href="{{ URL::to('/') }}/accounting/report/trial_balance"><i class="fa fa-folder"></i> Trial Balance</a>
								</li>
								@endif
								@if(hasPrivilege('profit_loss','view'))
								<li id="profit_loss">
									<a href="{{ URL::to('/') }}/accounting/report/profit_loss"><i class="fa fa-folder"></i> Profit Loss</a>
								</li>
								@endif
								@if(hasPrivilege('general_ledger','view'))
								<li id="general_ledger">
									<a href="{{ URL::to('/') }}/accounting/report/general_ledger"><i class="fa fa-folder"></i> General Ledger</a>
								</li>
								@endif
							</ul>
						</li>
						@endif
					</ul>
				</li>
				@endif
				@if(hasPrivilege('user_management','view'))
				<li id="user_management">
					<a href="javascript:;">
						<i class="fa fa-folder-open"></i>  
						<span class="title">User Management</span>
						<span class="arrow"></span>
					</a>
					<ul class="sub-menu">
						@if(hasPrivilege('user_group','view'))
						<li id="user_group">
							<a href="{{ URL::to('/') }}/user_group"><i class="fa fa-folder"></i> User Group</a>
						</li>
						@endif
						@if(hasPrivilege('user_account','view'))
						<li id="user_account">
							<a href="{{ URL::to('/') }}/user_account"><i class="fa fa-folder"></i> User Account</a>
						</li>
						@endif
					</ul>
				</li>
				@endif
			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
	</div>
	