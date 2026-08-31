<div class="top-content" id="get_user_payments">
   <h3 class="page-title">Fetch All Transactions</h3>
   <p>
      Fetch all transactions with HTTP GET request.
   </p>
   <p>
      API token is required for the authentication of the calling program to the API.
   </p>
   <p>
      Here the transactions list is displayed in a paginated manner. You can fetch data by passing the 'page_id' and 'per_page' parameters. <br>
      page_id is the index of your page, so you can pass your page id. <br>
      Ex.: To display on the first page, pass 1; to display on the second page, pass 2 <br>
      per_page is the number of items displayed per page. <br>
   </p>
</div>
<div class="row">
   <div class="col-md-6">
      <!-- TABLE HOVER -->
      <div class="card border-0 shadow-sm mb-3">
         <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Request</h3><br>
            <span class="text-warning">GET</span> : <?=base_url();?>user/all_transactions</p>
         </div>
         <div class="card-body">
            <table class="table table-hover">
               <thead>
                  <tr>
                     <th>Parameter</th>
                     <th>Type</th>
                     <th>Position</th>
                     <th>Description</th>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <td>page_id</td>
                     <td><code>number</code></td>
                     <td><code>Query</code></td>
                     <td>Pass the page number index for paginated data display.</td>
                  </tr>
                  <tr>
                     <td>per_page</td>
                     <td><code>number</code></td>
                     <td><code>Query</code></td>
                     <td>Set the number of items you want to display per page.</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
      <!-- END TABLE HOVER -->
   </div>
   <div class="col-md-6">
      <!-- TABLE HOVER -->
      <div class="card border-0 shadow-sm mb-3">
         <div class="card-header bg-success text-white">
            <h3 class="card-title mb-0">Response</h3>
         </div>
         <div class="card-body">
            <pre class="response-view">{
    "status": true,
    "message": "Transactions fetched successfully",
    "currency_symbol": "$",
    "currency_code": "USD",
    "enable_shorten_numbers": 1,
    "data": [
        {
            "module": "membership",
            "id": "4",
            "user_id": "3",
            "username": "aff1",
            "price": "3",
            "payment_gateway": "Default",
            "payment_detail": "[]",
            "status_id": "1",
            "datetime": "2023-10-27 08:52:33"
        }
    ]
}</pre>
            <pre class="response-view">{"status":false,"message":"Unauthorized Access!"}</pre>
         </div>
      </div>
      <!-- END TABLE HOVER -->
   </div>
</div>
