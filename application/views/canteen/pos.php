<style>
.pos-layout { display:grid; grid-template-columns:1fr 360px; gap:20px; height:calc(100vh - 130px); }
.pos-menu   { overflow-y:auto; }
.pos-cart   { background:#fff; border-radius:10px; border:1px solid #e2e8f0; display:flex; flex-direction:column; overflow:hidden; }
.item-card  { border:1px solid #e2e8f0; border-radius:8px; padding:12px; cursor:pointer; transition:.15s; background:#fff; }
.item-card:hover { border-color:#6366f1; background:#eef2ff; transform:translateY(-1px); box-shadow:0 2px 8px rgba(99,102,241,.15); }
.item-price { font-size:1.1rem; font-weight:700; color:#6366f1; }
.item-cat   { font-size:.7rem; background:#e0e7ff; color:#3730a3; border-radius:4px; padding:1px 6px; }
.cart-body  { flex:1; overflow-y:auto; padding:12px; }
.cart-footer{ padding:14px; border-top:1px solid #e2e8f0; }
.cart-row   { display:flex; align-items:center; gap:8px; padding:6px 0; border-bottom:1px solid #f1f5f9; }
.cart-remove{ background:none; border:none; color:#ef4444; cursor:pointer; font-size:1rem; }
.wallet-info{ background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:12px 14px; margin-bottom:14px; }
.student-found{ background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; padding:12px 14px; margin-bottom:14px; }
@media(prefers-color-scheme:dark){
  .pos-cart,.item-card { background:#2b2b3a; border-color:#3a3a50; }
  .item-card:hover { background:#3a3a55; }
  .wallet-info { background:#0f2d1a; border-color:#166534; }
  .student-found { background:#0f1f3d; border-color:#1e40af; }
  .cart-row { border-color:#3a3a50; }
  .cart-footer { border-color:#3a3a50; }
}
</style>

<div style="padding:16px 20px 0;">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0"><i class="fas fa-cash-register me-2 text-success"></i>Canteen POS</h4>
        <div class="d-flex gap-2">
            <a href="<?php echo base_url('canteen/wallets'); ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-wallet me-1"></i>Wallets</a>
            <a href="<?php echo base_url('canteen/menu'); ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-utensils me-1"></i>Menu</a>
            <a href="<?php echo base_url('canteen/report'); ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-chart-bar me-1"></i>Report</a>
        </div>
    </div>
</div>

<div class="pos-layout" style="padding:0 20px 20px;">
    <!-- Left: Menu grid -->
    <div class="pos-menu">
        <?php
        $grouped = [];
        foreach ($items as $item) { $grouped[$item->category ?: 'General'][] = $item; }
        ?>
        <?php foreach ($grouped as $cat => $catItems): ?>
        <h6 class="text-muted text-uppercase small fw-bold mb-2 mt-3"><?php echo html_escape($cat); ?></h6>
        <div class="row g-2 mb-3">
            <?php foreach ($catItems as $item): ?>
            <div class="col-sm-4 col-md-3">
                <div class="item-card text-center" onclick="addToCart(<?php echo $item->id; ?>, '<?php echo html_escape(addslashes($item->name)); ?>', <?php echo $item->price; ?>)">
                    <div class="fw-semibold mb-1" style="font-size:.9rem;"><?php echo html_escape($item->name); ?></div>
                    <div class="item-price">KES <?php echo number_format($item->price,2); ?></div>
                    <?php if ($item->quantity_in_stock !== null): ?>
                    <div class="text-muted small mt-1">Stock: <?php echo $item->quantity_in_stock; ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Right: Cart / Student -->
    <div class="pos-cart">
        <div style="padding:14px; border-bottom:1px solid #e2e8f0;">
            <div class="fw-semibold mb-2"><i class="fas fa-user-circle me-1"></i>Student</div>
            <div class="input-group input-group-sm">
                <input type="text" id="student-search" class="form-control" placeholder="Adm. No or Student ID" onkeypress="if(event.key==='Enter'){lookupStudent()}">
                <button class="btn btn-primary" onclick="lookupStudent()">Find</button>
            </div>
            <div id="student-info" style="display:none;" class="student-found mt-2">
                <div class="d-flex align-items-center gap-2">
                    <div>
                        <div id="s-name" class="fw-semibold"></div>
                        <div id="s-adm" class="text-muted small"></div>
                    </div>
                    <div class="ms-auto">
                        <div class="small text-muted">Balance</div>
                        <div id="s-bal" class="fw-bold" style="color:#059669; font-size:1.1rem;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="cart-body">
            <div class="fw-semibold mb-2"><i class="fas fa-shopping-basket me-1"></i>Cart</div>
            <div id="cart-items">
                <div class="text-center text-muted py-4" id="cart-empty">
                    <i class="fas fa-shopping-basket fa-2x mb-2"></i><br>Cart is empty
                </div>
            </div>
        </div>

        <div class="cart-footer">
            <div class="d-flex justify-content-between mb-2 fw-semibold">
                <span>Total</span>
                <span id="cart-total">KES 0.00</span>
            </div>
            <button class="btn btn-success w-100 btn-lg" onclick="processPayment()">
                <i class="fas fa-check-circle me-2"></i>Charge to Wallet
            </button>
            <button class="btn btn-outline-secondary w-100 mt-2 btn-sm" onclick="clearCart()">
                <i class="fas fa-trash me-1"></i>Clear
            </button>
        </div>
    </div>
</div>

<script>
var cart = {};
var currentStudentId = null;
var currentBalance   = 0;

function lookupStudent() {
    var v = document.getElementById('student-search').value.trim();
    if (!v) return;
    $.post(base_url+'canteen/get_student_wallet', $.extend({student_id:v}, csrfData), function(r){
        r = typeof r==='string' ? JSON.parse(r) : r;
        if (r.status==='success') {
            currentStudentId = r.student.id;
            currentBalance   = parseFloat(r.balance);
            document.getElementById('s-name').textContent = r.student.firstname+' '+r.student.lastname;
            document.getElementById('s-adm').textContent  = 'Adm: '+r.student.admission_no;
            document.getElementById('s-bal').textContent  = 'KES '+parseFloat(r.balance).toFixed(2);
            document.getElementById('student-info').style.display = '';
        } else {
            alert(r.msg); currentStudentId=null;
            document.getElementById('student-info').style.display='none';
        }
    });
}

function addToCart(id, name, price) {
    if (!cart[id]) cart[id]={id:id,name:name,price:price,qty:0};
    cart[id].qty++;
    renderCart();
}

function removeFromCart(id) { delete cart[id]; renderCart(); }
function changeQty(id,delta) {
    cart[id].qty += delta;
    if (cart[id].qty <= 0) delete cart[id];
    renderCart();
}

function renderCart() {
    var keys = Object.keys(cart);
    var html = '';
    var total = 0;
    if (!keys.length) {
        document.getElementById('cart-empty').style.display='';
    } else {
        document.getElementById('cart-empty').style.display='none';
        keys.forEach(function(k){
            var it=cart[k];
            var sub=it.price*it.qty;
            total+=sub;
            html+='<div class="cart-row"><div style="flex:1"><div class="small fw-semibold">'+it.name+'</div><div class="text-muted" style="font-size:.78rem;">KES '+it.price.toFixed(2)+' x '+it.qty+'</div></div>'
                 +'<div class="d-flex align-items-center gap-1">'
                 +'<button class="btn btn-xs btn-outline-secondary" onclick="changeQty('+k+',-1)">−</button>'
                 +'<span class="small px-1">'+it.qty+'</span>'
                 +'<button class="btn btn-xs btn-outline-secondary" onclick="changeQty('+k+',1)">+</button>'
                 +'</div>'
                 +'<span class="small fw-semibold" style="min-width:70px;text-align:right;">'+sub.toFixed(2)+'</span>'
                 +'<button class="cart-remove" onclick="removeFromCart('+k+')"><i class="fas fa-times"></i></button>'
                 +'</div>';
        });
    }
    document.getElementById('cart-items').innerHTML = (keys.length?'':document.getElementById('cart-empty').outerHTML) + html;
    document.getElementById('cart-total').textContent = 'KES '+total.toFixed(2);
}

function clearCart() { cart={}; renderCart(); }

function processPayment() {
    if (!currentStudentId) { alert('Please select a student first.'); return; }
    var keys = Object.keys(cart);
    if (!keys.length) { alert('Cart is empty.'); return; }
    var total=0; keys.forEach(function(k){ total+=cart[k].price*cart[k].qty; });
    if (total > currentBalance) { alert('Insufficient balance. Student has KES '+currentBalance.toFixed(2)+' but total is KES '+total.toFixed(2)); return; }

    if (!confirm('Charge KES '+total.toFixed(2)+' from student wallet?')) return;

    $.post(base_url+'canteen/checkout_pos', $.extend({
        student_id: currentStudentId,
        cart: JSON.stringify(Object.values(cart))
    }, csrfData), function(r){
        r = typeof r==='string' ? JSON.parse(r) : r;
        if (r.status==='success') {
            alert('✓ Sale complete!\nReceipt: '+r.receipt_no+'\nTotal: KES '+r.total+'\nNew Balance: KES '+r.new_balance);
            currentBalance = parseFloat(r.new_balance);
            document.getElementById('s-bal').textContent = 'KES '+r.new_balance;
            clearCart();
        } else {
            alert('Error: '+r.msg);
        }
    });
}
</script>
