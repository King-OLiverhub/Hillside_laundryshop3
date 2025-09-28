@extends('layouts.app')
@section('content')
<h1>Customers</h1>
<div class="card mb-3"><div class="card-body">
  <div class="row g-2">
    <div class="col"><input id="cfname" placeholder="First name" class="form-control"></div>
    <div class="col"><input id="cmname" placeholder="Middle name" class="form-control"></div>
    <div class="col"><input id="clname" placeholder="Last name" class="form-control"></div>
    <div class="col"><input id="ccontact" placeholder="Contact" class="form-control"></div>
    <div class="col-md-2">
      <select id="ctype" class="form-select">
        <option value="walkin">Walk-in</option>
        <option value="regular">Regular</option>
      </select>
    </div>
    <div class="col-md-2">
      <button id="createCustomer" class="btn btn-primary">Create</button>
    </div>
  </div>
</div></div>

<div class="card"><div class="card-body">
  <h5>Customer List</h5>
  <table class="table table-sm" id="customerTbl">
    <thead><tr><th>Name</th><th>Contact</th><th>Type</th></tr></thead>
    <tbody></tbody>
  </table>
</div></div>

<script>
const api = p=> '/'+p.replace(/^\/+/,'');
function loadCustomers(){
  axios.get(api('api/customers')).then(r=>{
    const rows = r.data.map(c=>`<tr><td>${c.first_name} ${c.middle_name||''} ${c.last_name}</td><td>${c.contact||''}</td><td>${c.customer_type}</td></tr>`).join('');
    document.querySelector('#customerTbl tbody').innerHTML = rows;
    const sel = document.getElementById('customer');
    if(sel){ sel.innerHTML = '<option value=\"\">-- Walk-in / Select --</option>'; r.data.forEach(c=> sel.insertAdjacentHTML('beforeend', `<option value="${c.id}">${c.first_name} ${c.last_name} (${c.customer_type})</option>`)); }
  });
}
document.getElementById('createCustomer').addEventListener('click', ()=>{
  const payload = {
    first_name: document.getElementById('cfname').value,
    middle_name: document.getElementById('cmname').value,
    last_name: document.getElementById('clname').value,
    contact: document.getElementById('ccontact').value,
    customer_type: document.getElementById('ctype').value
  };
  axios.post(api('api/customers'), payload).then(()=> { loadCustomers(); document.getElementById('cfname').value=''; document.getElementById('cmname').value=''; document.getElementById('clname').value=''; document.getElementById('ccontact').value=''; })
  .catch(err=> alert('Error: '+(err.response?.data?.message||err.message)));
});
window.addEventListener('DOMContentLoaded', loadCustomers);
</script>
@endsection
