<style>
  body { margin-top: 45px; }

  h3, h4, h5{
    padding: 0;
    margin: 0;
  }

  .cr {
    width: 400px;
    padding: 8px 16px;
    position: fixed;
    text-align: center;
    color: #ffffff;
  }
  
  .cr-sticky {
    position: fixed;
  }
  
  /* Positions */
  
  .cr-top    { top: -280px; }
  .cr-bottom { bottom: 25px; }
  .cr-left   { left: -50px; }
  .cr-right  { right: -200px; }
  
  /* Rotations */
  
  .cr-top.cr-left,
  .cr-bottom.cr-right {
    transform: rotate(-45deg);
  }
  
  .cr-top.cr-right,
  .cr-bottom.cr-left {
    transform: rotate(35deg);
  }
  
  /* Colors */
  
  .cr-unpaid { 
    background-color: #df554a;
    border-bottom: 3px solid #ab312b;
    border-top: 3px solid #ab312b;
    font-weight: 1000;
    font-family: Helvetica;
  }

  .cr-paid h3, .cr-unpaid h3 { 
    font-size: 25px;
  }

  .cr-paid { 
    background-color: #97df4a;
    border-bottom: 3px solid #6ec046;
    border-top: 3px solid #6ec046;
    font-weight: 1000;
    font-family: Helvetica;
  }
  
  @page{
    margin-top: 300px; /* create space for header */
  }
  header{
    position: fixed;
    left: 0px;
    right: 0px;
  }
  header{
    height: 60px;
    margin-top: -200px;
  }

</style>

<body>
<header>
<table style="width: 100%;">
  <tr>
    <td style="vertical-align: bottom;">
      <table>
        <tr>
          <td>
            <h4>TectoniCorp, PC </h4>
            <h4>Princeton Engineering</h4>
            <h5>35091 Paxson Road</h5>
            <h5>Round Hill, VA 20141</h5>
            <h5>Tel: 540-313-5317</h5>
            <h5>Fax: 877-455-5641</h5>
          </td>
          <td>
            <img style="height: 96px; margin-left: 20px;" src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/4RB2RXhpZgAATU0AKgAAAAgAAwExAAIAAAARAAAIPodpAAQAAAABAAAIUOocAAcAAAgMAAAAMgAAAAAc6gAAAAgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAFBhaW50Lk5FVCB2My41LjEAAAAB6hwABwAACAwAAAhiAAAAABzqAAAACAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA/+EKVGh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8APD94cGFja2V0IGJlZ2luPSfvu78nIGlkPSdXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQnPz4NCjx4bXA6eG1wbWV0YSB4bWxuczp4bXA9ImFkb2JlOm5zOm1ldGEvIj48cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPjxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSJ1dWlkOmZhZjViZGQ1LWJhM2QtMTFkYS1hZDMxLWQzM2Q3NTE4MmYxYiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIj48eG1wOmNyZWF0b3J0b29sPlBhaW50Lk5FVCB2My41LjE8L3htcDpjcmVhdG9ydG9vbD48L3JkZjpEZXNjcmlwdGlvbj48cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0idXVpZDpmYWY1YmRkNS1iYTNkLTExZGEtYWQzMS1kMzNkNzUxODJmMWIiIHhtbG5zOnRpZmY9Imh0dHA6Ly9ucy5hZG9iZS5jb20vdGlmZi8xLjAvIj48dGlmZjpzb2Z0d2FyZT5QYWludC5ORVQgdjMuNS4xPC90aWZmOnNvZnR3YXJlPjwvcmRmOkRlc2NyaXB0aW9uPjwvcmRmOlJERj48L3htcDp4bXBtZXRhPg0KPD94cGFja2V0IGVuZD0ndyc/PiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAg/9sAQwALBwgKCAcLCgkKDAwLDRAbEhAPDxAhGBkUGycjKSknIyYlLDE/NSwuOy8lJjZKNztBQ0ZHRio0TVJMRFI/RUZD/9sAQwEMDAwQDhAgEhIgQy0mLUNDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0ND/8AAEQgBAAEvAwEiAAIRAQMRAf/EAB8AAAEFAQEBAQEBAAAAAAAAAAABAgMEBQYHCAkKC//EALUQAAIBAwMCBAMFBQQEAAABfQECAwAEEQUSITFBBhNRYQcicRQygZGhCCNCscEVUtHwJDNicoIJChYXGBkaJSYnKCkqNDU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6g4SFhoeIiYqSk5SVlpeYmZqi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">
          </td>
        </tr>
      </table>
    </td>
    <td style="vertical-align: bottom; text-align: right;">  
      <h3 style="margin: 10px 0px;">Invoice #{{ $curBill->id }}</h3>
      <h5 style="margin: 10px 0px;">Invoice Date: {{ $invoiceDate }}</h5>
      <h5 style="margin: 10px 0px;">Due Date: {{ $dueDate }}</h5>
    </td>
    </tr>
  </table>

<table style="margin-top: 30px;">
  <tr>
    <td style="vertical-align: top;">
      <h4>{{ $company->company_name }}</h4>
      @if($company->attn_name != '')
      <h5>Attn: {{ $company->attn_name }}</h5>
      @endif
      <h5>{{ $company->company_address }}</h5>
      @if($company->second_address != '')
      <h5>{{ $company->second_address }}</h5>
      @endif
      <h5>{{ $company->city . ", " . $company->state . " " . $company->zip }}</h5>
    </td>
    @if($billInfo->billing_name != '')
    <td style="vertical-align: top;">
      <div style="margin-left: 20px;">
      <h4>Bill To</h4>
      <h5>{{ $billInfo->billing_name }}</h5>
      <h5>{{ $billInfo->billing_address }}</h5>
      <h5>{{ $billInfo->billing_city }}, {{ $billInfo->billing_state }}, {{ $billInfo->billing_zip }}</h5>
      </div>
    </td>
    @endif
  </tr>
</table>
</header>

<main>
<div class="cr cr-top cr-right cr-sticky cr-{{ $type ? 'paid': 'unpaid' }}">
  <h3>{{ $type ? 'PAID': 'UNPAID' }}</h3>
</div>

<div style="text-align: center; margin-top: 30px;">
  <h3>Engineering Services from {{ $curBill->issuedFrom }} to {{ $curBill->issuedTo }}, {{ count($jobs) }} jobs total</h3>
</div>

<table style="width: 100%; border: 1px solid #cccccc; font-size: 14px; margin-top: 10px;">
  <thead>
    <tr style="background: #efefef;">
      <th style="width: 15%; text-align: center; border: 1px solid #efefef;">DATE</th>
      <th style="width: 15%; text-align: center; border: 1px solid #efefef;">CODE</th>
      <th style="width: 35%; text-align: center; border: 1px solid #efefef;">DESCRIPTION</th>
      <th style="width: 10%; text-align: center; border: 1px solid #efefef;">QUANTITY</th>
      <th style="width: 10%; text-align: center; border: 1px solid #efefef;">UNIT PRICE</th>
      <th style="width: 15%; text-align: center; border: 1px solid #efefef;">AMOUNT</th>
    </tr>
  </thead>
  <tbody>
    {{-- <tr>
      <td>Monthly base number of jobs</td>
      <td style="text-align: center;">{{ $curBill->notExceeded }}</td>
      <td style="text-align: center;">${{ $billInfo->base_fee }}</td>
      <td style="text-align: center;">${{ $billInfo->base_fee * $curBill->notExceeded }}</td>
    </tr>
    <tr>
      <td>Jobs exceeding monthly base</td>
      <td style="text-align: center;">{{ $curBill->exceeded }}</td>
      <td style="text-align: center;">${{ $billInfo->extra_fee }}</td>
      <td style="text-align: center;">${{ $billInfo->extra_fee * $curBill->exceeded }}</td>
    </tr> --}}
    @foreach($expenses as $expense)
    <tr>
      <td style="text-align: center;">{{ $expense->date }}</td>
      <td style="text-align: center;">{{ $expense->code }}</td>
      <td>{{ $expense->description }}</td>
      <td style="text-align: center;">{{ $expense->quantity }}</td>
      <td style="text-align: center;">${{ $expense->price }}</td>
      <td style="text-align: center;">${{ $expense->amount }}</td>
    </tr>
    @endforeach
    <tr style="background: #efefef;">
      <td></td>
      <td></td>
      <td>Total Price</td>
      <td></td>
      <td></td>
      <td style="text-align: center;">${{ $curBill->amount }}</td>
    </tr>
  </tbody>
</table>
<table style="width: 100%;">
  <td style="font-style: italic; font-size: 14px;">
    We thank you for your business. All unpaid balances over 30 days are charged interest at the rate of 1-1/2% per month (18% APR) which is added to the open account balance.
  </td>
</table>

<div style="text-align: center; margin-top: 50px;">
  <h3>Job Details</h3>
</div>

<table style="width: 100%; border: 1px solid #cccccc; font-size: 14px; margin-top: 10px;">
  <thead>
    <tr style="background: #efefef;">
      <th style="width: 10%; text-align: center; border: 1px solid #efefef;">Number</th>
      <th style="width: 15%; text-align: center; border: 1px solid #efefef;">User</th>
      <th style="width: 15%; text-align: center; border: 1px solid #efefef;">Project Number</th>
      <th style="width: 30%; text-align: center; border: 1px solid #efefef;">Project Name</th>
      <th style="width: 10%; text-align: center; border: 1px solid #efefef;">State</th>
      <th style="width: 20%; text-align: center; border: 1px solid #efefef;">Created Time</th>
    </tr>
  </thead>
  <tbody>
    <?php $i = 1; ?>
    @foreach($jobs as $job)
    <tr>
      <td style="text-align: center;">{{ $i }}</td>
      <td style="text-align: center;">{{ $job->username }}</td>
      <td style="text-align: center;">{{ $job->projectnumber }}</td>
      <td style="text-align: center;">{{ $job->projectname }}</td>
      <td style="text-align: center;">{{ $job->state }}</td>
      <td style="text-align: center;">{{ date('Y-m-d', strtotime($job->createdtime)) }}</td>
      <?php $i ++; ?>
    </tr>
    @endforeach
  </tbody>
</table>
</main>

</body>