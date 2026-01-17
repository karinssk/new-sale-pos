<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quotation PDF</title>

    <style>
  /* ตัด margin ของหน้าเอกสาร mPDF */
  /* @page { margin: 0; } */

  /* ตัด margin/padding ของ body/html ด้วย */
  /* html, body {
    margin: 0;
    padding: 0;
  } */

  /* ถ้า wrapper มี padding/margin ให้เคลียร์ด้วย */
  /* .page,
  .wrapper {
    margin: 0 !important;
    padding: 0 !important;
    box-sizing: border-box;
    width: 100%;
  } */
</style>

    <style>


        /* * {
            margin: 0 !important;
            padding: 0 !important;
            box-sizing: border-box;
        }
         */
        body {
            font-family: 'cordiabiupc', sans-serif;
            color: #000000;
            font-size: 14px;
            width: 100%;
        }
        
        .header-section {
            width: 100%;
            position: relative;
            
        }
        
        .company-header {
            position: absolute;
            left: 0px;
            top: 0px;
        }
        
        .company-name {
            font-family: 'smb';
            font-size: 38px;
            font-weight: bold;
            color: #dd232e;
            line-height: 0.9;
            margin-top: 120px;
            padding-top:10px;
            padding-left: 20px;
            padding-bottom: -40px;
        }
        
        .company-thai {
            font-family: 'dtac';
            font-size: 18px;
            font-weight: bold;
            color: #000;
            line-height: 0.4;
            margin: 0;
             padding-left: 20px;
             margin-left: 5px;
             margin-top: -10px;
             margin-bottom: -10px;
        }
        
        .quotation-title {
            text-align: right;
            margin-top: -56px;
            margin-right: 20px;
            padding-top: 10px;
        }
        
        .quotation-thai {
            font-family: 'dtac';
            font-size: 26px;
            font-weight: bold;
            color: #000;
            margin: 0;
            margin-bottom: -5px;
        }
        
        .quotation-eng {
            font-family: 'dtac';
            font-size: 16px;
            font-weight: bold;
            color: #000;
            margin: 0;
        }
        
        .red-band {
            font-family: 'cordiabiupc';
            background-color: #dd242f;
            height: 24px;
            color: #FFF;
            text-align: center;
            font-size: 13px;
            line-height: 24px;
            margin: 2px 0 0 0;
        }
        
        .gray-band {
            font-family: 'cordiabiupc';
            background-color: #676767;
            height: 24px;
            color: #FFF;
            text-align: center;
            font-size: 13px;
            line-height: 24px;
            margin: 0 0 5px 0;
            z-index: 9999;
        }
        
        .doc-info {
            font-family: 'freesiaupc';
            background: #fff;
            padding: 8px 20px;
            /* font-weight: bold; */
            margin-bottom: 5px;
        }
        
        .info-section {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .customer-section {
            float: left;
            width: 45%;
            padding: 8px;
            margin-left: 10px;
            margin-right: 2%;
            height: 100px;
            margin-top: 44px;
        }
        
        .sales-section {
            float: right;
            width: 45%;
            padding: 8px;
            margin-top: 0px;
            background: #fff;
            height: 100px;
        }
        
        .section-title {
            font-family: 'smb';
            background: #797b7d;
            color: white;
            padding: 5px;
            text-align: center;
            font-weight: bold;
            margin: -8px -8px 8px -8px;
            font-size: 12px;
        }
        
        .info-label {
            font-family: 'cordiabiupc';
            
            display: inline-block;
            width: 100px;
            text-align: right;
            margin-right: 8px;
            font-size: 12px;
        }
        
        .products-section {
            clear: both;
            margin-top: -578px;
            margin-left: 58px;
            z-index: 9999;
            margin-right:0px;
            
        }
        
        .products-title {
            font-family: 'smb';
            background: #797b7d;
            color: white;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        table {
            font-family: 'cordiabiupc';
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            z-index: 9999;
        }
        
        .table-header {
            font-family: 'smb';
            background: #dc8285;
            color: #fff;
            font-weight: bold;
        }
        .textcolor{
            color: #fff;
        }
        
        table td, table th {
            font-family: 'cordiabiupc';
            border: 1px solid #EAEAEA;
            padding: 4px;
            text-align: left;
            font-size: 12px;
            height: 30px;
            vertical-align: top;
            z-index: 9999;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .summary-table {
            float: right;
            width: 300px;
            padding-top: 120px;
          
            margin-bottom: -280px;
            padding-bottom: -20px;
            margin-top:200px;
        }
        
        .summary-table td {
            font-family: 'cordiabiupc';
            padding: 3px;
            font-size: 12px;
        }
        
        .total-row {
            background: #ee1515ff;
            color: #fff;
            font-weight: bold;
            font-size: 24px;
        }
        #total-rows {
            background: #fff7f7ff;
            color: #fff;
            font-weight: bold;
            font-size: 36px;
        }
        
        .signature-section {
            clear: both;
            margin-top: 320px;
            padding-top: -100px;
            padding-left:40px;
             
        }
        
        .signature-box {
            font-family: 'cordiabiupc';
            float: left;
            width: 22%;
            margin-right: 3%;
            
            padding: 8px;
            height: 100px;
            font-size: 10px;
            
        }
        
        .signature-title {
            font-family: 'cordiabiupc';
           
            text-align: center;
            padding: 5px;
            font-weight: bold;
            margin: -8px -8px 8px -8px;
            font-size: 11px;
        }
        

        .signature-title-header{
           background: #f8f8f8ff;
        } 

        .approve-box {
            background: #dd242f !important;
            color: #fff;
        }
        
        .signature-line {
            border-bottom: 1px dotted #000;
            margin-top: 20px;
            text-align: center;
            padding-top: 3px;
            font-size: 9px;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .info-value {
            font-family: 'cordiabiupc';
            font-size: 12px;
        }




     .info-section{
        background-color: #fff;
        padding-top: -254px;
        border:none;
        
        margin-left: 52px;
     }

     .vertical-text {
    writing-mode: vertical-lr; 
    transform: rotate(180deg); 
    background-color:#797b7d;
    color: #fff;
    
    width: 2px; 
    padding-left: -30px; 
    /* margin-bottom: 0px; */
    /* position: absolute; */
    margin-top:-6px;    
    /* padding-top: 100px; 
    padding-left: -1000px;
    text-align: center; 
    */
}


.ramark {
    position: absolute;
    bottom: 274px;
    left: 60px;
    width: 40%;
    height: 20px;
   

    text-align: left;
    margin-bottom: -80px;

}

.ramark-text {
    font-family: 'cordiabiupc';
    font-size: 12px;
    color: #000;
    margin-top: 10px;
    margin-left: 5px;
    display: block;
    padding-left: 5px;
    padding-right: 5px;
    margin-bottom: 10px;
    margin-right: 5px;
}
.wrapper {
  
   margin-left:12px;
   width: 400px; /* Set width of wrapper */
   height: 50px;
  /* Set background color of wrapper */
 }
  
  .section-title2{
   
    color: #000;
    padding: 5px;
    text-align: left;
   
    margin-top: -12px ;
    font-size: 12px;
 }


 .selecontainer {
   
    width: 100%;
    height: 50px;
    padding-top: -170px;
    margin-top:-100px;
    margin-left: 56px;
 }

th {
  padding: 0;
  margin: 0;
}

.filter1 {
    width: 2px;
    height: 165px;
   background-color:#797b7d;
    position: absolute; 
    top:126px;
    left: 34px;
    z-index: -1; /* Ensure it is behind other content */

}
.filter2 {
    width: 2px;
     height: 180px;
  background-color:#797b7d;
    position: absolute; 
    top:120px;
    right: 317px;
    z-index: -1; /* Ensure it is behind other content */

}



.filter3 {
    width: 2px;
     height: 554px;
    background-color:#797b7d;
    position: absolute; 
    top:292px;
    left: 34px;
    z-index: -1; /* Ensure it is behind other content */

}
.vertical-text2 {
    margin-top: -12px;
    border: none !important;
}



 .pagenumber{
        font-family: 'dtac';
        font-size: 12px;
        font-weight: bold;
        color: #000;
        margin-top: 4px;
        margin-right: 20px;
        text-align: right;
 }
.companyinfo{
    font-family: 'cordiabiupc';
    font-size: 16px;
    font-weight: 300;
}
.location {
    font-family: 'cordiabiupc';
    font-size: 12px;
    color: #000;
    margin-top: 5px;
    display: block;
    margin-left: 5px;
}

.remark {
    position: absolute;
    bottom: 270px;
    padding: 12px 16px;
    margin-top: 10px;
 
    left: 60px;
    border-radius: 8px;
    font-size: 13px;
    line-height: 1.6;
    color: #000; /* สีดำปกติ */
    margin-bottom: -80px;
}

.remark span {
    display: block;
    font-weight: bold;
    margin-bottom: 6px;
    color: #000; /* หมายเหตุ เป็นสีดำ */
}

.remark p {
    margin: 4px 0;
    color: #000; /* ทุกบรรทัดเป็นสีดำ */
}

.remark-warning {
    color: red !important;       /* บรรทัดสุดท้ายเป็นสีแดง */
    font-weight: bold;
    bottom: 140px;
    position: absolute;
}


#warning {
    color: red !important;       /* บรรทัดสุดท้ายเป็นสีแดง */
    font-weight: bold;
    margin-top: 10px;
    font-size: 12px;
}


</style>













<style>



.singnaturelineapprove {
    border-bottom: 1px dotted #000;
    margin-top: 60px;
    text-align: center;
    padding-top: 3px;
    font-size: 9px;
}



/* list container */
.signature-box .terms{
  font-size: 6pt;        /* use pt for PDFs */
  line-height: 1.75;      /* line spacing */
  word-spacing: .08em;    /* Thai-safe looseness between words */
}

/* bullets */
.signature-box ul{
  margin: 0;
  padding: 0 0 0 14pt;    /* indent bullets */
  list-style: disc;
  list-style-position: outside;
}

.signature-box li{
  margin: 0 0 6pt 0;      /* space between items */
  padding: 0;
}

/* Optional: character spacing for Latin only (not Thai) */
.latin { letter-spacing: .3pt; }

.terms2 {
    
    padding-left:20px;
}

.realtotal{
    background-color: #0c0c0cff;
}
</style>

</head>


<!-- first page here >>>>>>>>>>>>>>>> -->

<body>
    @php
        $products_per_page = 5;
        $total_products = count($receipt_details->lines);
        $total_pages = $total_products > $products_per_page ? ceil($total_products / $products_per_page) : 1;
        $is_multi_page = $total_products > $products_per_page;
    @endphp
      

     <div class="filter1"></div>
      <div class="filter2"></div>
      <div class="filter3"></div>
<div class="remark">
    <span>หมายเหตุ:</span>
    <p>รับประกันซ่อมฟรี 1 ปี (ไม่ร่วมอะไหล่)</p>
    <p>มีค่าใช้จ่ายในการ รับ - ส่ง (กรณีส่งซ่อม)</p>
    <p>เซอร์วิส หลังการขาย ส่งสิ้นค้าเข้าศูนย์บริการที่ดอนเมือง</p>
   
</div>
 


    <!-- First Page -->
    <div class="page-content">

        <!-- Header Section -->
        <div class="header-section">
            <div class="company-header">
                <p class="company-name">RUBYSHOP</p>
                    <p class="company-thai">ห้างหุ้นส่วนจำกัดรูบี้ช๊อป</p>
            </div>
            
            <div class="quotation-title">
                <p class="quotation-thai">ใบเสนอราคา</p>
                <p class="quotation-eng">QUOTATION</p>
                <p class="pagenumber">หน้าที่ 1/{{ $total_pages }}</p>
            </div>
        
            <!-- <div style="clear: both; height: 60px;"></div> -->
            
            <!-- @if(!empty($receipt_details->address))
                <div style="text-align: center; margin: 5px 0; font-size: 13px;">
                    {!! $receipt_details->address !!}
                </div>
            @endif -->
            
        


            <div class="red-band">
           RUBYSHOP PART.,LTD. 97/60, Lak Si,  Lak Si Land Village, Vibhavadi rangsit ROAD, DON MUANG, BANGKOK, 10210
            </div>
            
            <div class="gray-band">
           COMPANY ID: 0-10355-5019171 TEL:(+66) 8-9666-7802 FAX : (+662) 981-1584 www.rubyshop.co.th
            </div>
        </div>




<div class="wrapper">       
 <table class="vertical-text">        
    <tr>
        <th text-rotate="100">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CLIENT INFORMATION &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </th>
        
        <th text-rotate="100">&nbsp;&nbsp;&nbsp;&nbsp;ข้อมูลลูกค้า&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    </tr>    
  </table>
</div>



        <!-- Customer and Sales Information -->
        <div class="info-section clearfix">
            <div class="customer-section">
                <!-- <div class="section-title">ที่อยู่ลูกค้า</div> -->
                   <div class="doc-info">
            Date : {{ $receipt_details->invoice_date }}  &nbsp;&nbsp;&nbsp; เลขที่ใบเสร็จรับเงิน : {{ $receipt_details->invoice_no }}
           
            
        </div>
                <div style="margin-bottom: 5px;">
                    <span class="info-label">ชื่อบริษัท :</span>
                    <span class="info-value">
                @if(!empty($receipt_details->customer_info))
              @php
               $customer_info = strip_tags($receipt_details->customer_info);

            // Get everything before the first number (address starts with number)
            if (preg_match('/^(.*?)(?=\d)/u', $customer_info, $matches)) {
             // Trim spaces and commas at the end
              $company_name = rtrim(trim($matches[1]), ',');
            } else {
             $company_name = $customer_info; // fallback
           }
            @endphp

           {{ $company_name }}
              @endif





                    </span>
                </div>
                
                <div style="margin-bottom: 5px;">
                    <span class="info-label">เลขประจำตัวผู้เสียภาษี :</span>
                    <span class="info-value">
                        @if(!empty($receipt_details->customer_tax_number))
                            {{ $receipt_details->customer_tax_number }}
                        @endif
                    </span>
                </div>
                 <dib>
                <div style="margin-bottom: 5px;">
                    <span class="info-label">ที่อยู่ :</span>
                    <span class="info-value">
         @if(!empty($receipt_details->customer_info))
         @php
        $customer_info = strip_tags($receipt_details->customer_info);

        // Remove everything before the first number (address usually starts with number)
        $customer_info = preg_replace('/^.*?(\d{1,5}\s?.*)$/u', '$1', $customer_info);

        // Remove "Mobile:" part if exists
        $customer_info = preg_replace('/Mobile:.*/u', '', $customer_info);

        $address = trim($customer_info);
         @endphp

      {{ $address }}
      @endif
        </span>
                 </div>
                <div style="margin-bottom: 5px;">
                    <span class="info-label">เบอร์โทร :</span>
                    <span class="info-value">
                    @if(!empty($receipt_details->customer_info))
    @php
        $customer_info = strip_tags($receipt_details->customer_info);

        // Regex to extract phone number after Mobile:, Tel:, Phone:, or โทร:
        if (preg_match('/(?:Tel:|Phone:|โทร:|Mobile:)\s*([0-9\-\+\(\)\s]+)/u', $customer_info, $matches)) {
            $phone = trim($matches[1]);
        } else {
            $phone = '-';
        }
    @endphp

    {{ $phone }}
@else
    -
@endif

                    </span>
                </div>
            </div>

            <div class="sales-section">
                
                <table class="vertical-text vertical-text2">
           
    <tr>
        <th text-rotate="100">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;COOMPANY INFORMATION &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        
        <th text-rotate="100">&nbsp;ข้อมูลผู้เสนอราคา&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        
    </tr>
    
  </table> <div class="selecontainer">
                 <div class="section-title2">
     
             </div>
             
                <div style="margin-bottom: 5px;">
                            <span class="companyinfo">หจก.รูบี้ช๊อป (สำนักงานใหญ่)</span><br />
                        <span class="location">เลขที่ 97/60 หมู่บ้านหลักสี่แลนด์ ซอยโกสุมรวมใจ39</span>
						<span class="location">แขวงดอนเมือง เขตดอนเมือง กรุงเทพฯ 10210</span><br/>
                    
                   
             
                </div>
                
                <div style="margin-bottom: 5px;">
                    <span class="info-label">เลขประจำตัวผู้เสียภาษี : 0103555019171</span>
                    <span class="info-value"></span>
                </div>
                
                <div style="margin-bottom: 5px;">
                    <span class="info-label">เบอร์โทรศัพท์: 089-666-7802</span>
                    <span class="info-value"></span>
                </div>
                
                <div style="margin-bottom: 5px;">
                    <span class="info-label">อีเมล์ :info@rubyshop.co.th</span>
                    <span class="info-value"></span>
                </div>

                      <span class="info-label">ขายโดย :</span>
                    <span class="info-value">
                        @if(!empty($receipt_details->delivery_person_user->first_name))
                            {{ $receipt_details->delivery_person_user->surname }} {{ $receipt_details->delivery_person_user->first_name }} {{ $receipt_details->delivery_person_user->last_name }}
                        @elseif(!empty($receipt_details->sales_person))
                            {{ $receipt_details->sales_person }}
                        @else
                            -
                        @endif
                    </span>



            </div>
          </div>
        </div>
<div class="wrapper">       
 <table class="vertical-text">        
    <tr>
        <th text-rotate="100">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PRODUCTS ADN SERVICES DESCRIPTION&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        
        <th text-rotate="100">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;สินค้าและบริการ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>

    </tr>    
  </table>
</div>
        <!-- Products Section -->
        <div class="products-section">
            <!-- <div class="products-title">
                PRODUCTS AND SERVICES DESCRIPTION / สินค้าและบริการ
            </div>
             -->


             
            <table>
                <tr class="table-header">
                    <td width="45%" class="textcolor">Description of Services and Goods</td>
                    <td width="15%" class="text-center textcolor">Quantity</td>
                    <td width="20%" class="text-center textcolor">Price Per Unit<br />(Baht)</td>
                    <td width="20%" class="text-center textcolor">Amount</td>
                </tr>

                @php
                    $first_page_products = array_slice($receipt_details->lines, 0, $products_per_page);
                @endphp

                @for($i = 0; $i < $products_per_page; $i++)
                    <tr>
                        @if(isset($first_page_products[$i]))
                            @php $line = $first_page_products[$i]; @endphp
                            <td>
                                {{ $i + 1 }} | 
                                {{ $line['name'] }} {{ $line['product_variation'] }} {{ $line['variation'] }}
                                @if(!empty($line['sub_sku']))
                                    <br>{{ $line['sub_sku'] }}
                                @endif
                                @if(!empty($line['product_description']))
                                    <br>{!! $line['product_description'] !!}
                                @endif
                                @if(!empty($line['sell_line_note']))
                                    <br>{!! $line['sell_line_note'] !!}
                                @endif
                            </td>
                            <td class="text-center">{{ $line['quantity'] }} {{ $line['units'] }}</td>
                            <td class="text-center">{{ $line['unit_price_before_discount'] }}</td>
                            <td class="text-right">{{ $line['line_total'] }}</td>
                        @else
                            <td>&nbsp;</td>
                            <td class="text-center">&nbsp;</td>
                            <td class="text-center">&nbsp;</td>
                            <td class="text-right">&nbsp;</td>
                        @endif
                    </tr>
                @endfor
            </table>
        </div>

        <!-- Summary Section -->
        <div class="summary-table">
            <table>
                <tr>
                    <td class="text-right">Subtotal:</td>
                    <td class="text-right">{{ $is_multi_page ? '' : $receipt_details->subtotal }}</td>
                </tr>
                @if(!empty($receipt_details->discount))
                    <tr>
                        <td class="text-right">Discount:</td>
                        <td class="text-right">{{ $is_multi_page ? '' : $receipt_details->discount }}</td>
                    </tr>
                @endif
                @if(!empty($receipt_details->tax))
                    <tr>
                        <td class="text-right">Tax:</td>
                        <td class="text-right">{{ $is_multi_page ? '' : $receipt_details->tax }}</td>
                    </tr>
                @endif
                <tr class="total-row" id="total-rows">
                    <td class="text-right">Total1:</td>
                    <td class="text-right">{{ $is_multi_page ? '' : $receipt_details->total }}</td>
                </tr>
            </table>
        </div>





    
</div>
        <!-- Signature Section (always show on first page if single page, or if multi-page show empty version) -->
        <div class="signature-section clearfix    ">
            <div class="signature-box ">
                <div class="signature-title signature-title-header">ผู้รับสินค้า<br />Received By</div>
                <div style="font-size: 9px; line-height: 1.1;" class="terms2 latin">
                  <p>ได้รับสินค้าครบตามรายการพร้อม</p> 
                    <p> ได้รับใบกำกับภาษีเรียบร้อยแล้ว</p>
                    <p> โปรดลงลายมือชื่อด้วยตัวบรรจง </p>
                </div>
                <div class="signature-line">
                    ผู้รับสินค้า/Received By<br />
                    วันที่/Date
                   

                </div>
            </div>

            <div class="signature-box ">
                <div class="signature-title signature-title-header">เงื่อนไขข้อมตกลง<br />Terms and Conditions</div>
                <div style="font-size: 8px; line-height: 1.0;">
                    <ul style="margin: 3px 0; padding-left: 12px;" class="terms latin">
                        <li>ได้รับสินค้าตามรานการข้างต้นนี้ถูกต้อง และยินยอมในเงื่อนไขตามเอกสารนี้</li>
                        <li>กรุณาสั่งจ่ายเช็คขีดคร่อมในนาม {{ $receipt_details->display_name ?: 'ห้างหุ้นส่วนจำกัดรูบี้ช๊อป' }}</li>
                        <li>บริษัทจะคิดอัตราดอกเบี้ย 1.5% ต่อเดือนสำหรับใบแจ้งหนี้ที่ไม่ชำระตามกำหนด</li>
                        
                    </ul>
                </div>
            </div>

            <div class="signature-box terms latin " >
                <div class="signature-title signature-title-header">ผู้ส่งสินค้า<br />Delivery By</div>
                <div class="signature-line">
                    ผู้ตรวจสอบสินค้า/QC1<br />
                    ผู้ส่งสินค้า/Delivered By
                </div>
            </div>

            <div class="signature-box">
                <div class="signature-title approve-box">Approve By/ผู้มีอํานาจอนุมัติ<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                <div class="signature-line singnaturelineapprove">
                    
                </div>
            </div>
        </div>
    </div>

    @if($is_multi_page)
        @php
            $remaining_products = array_slice($receipt_details->lines, $products_per_page);
            $chunks = array_chunk($remaining_products, $products_per_page);
            $current_index = $products_per_page;
        @endphp

        @foreach($chunks as $page_num => $chunk)
            <div class="page-break"></div>
            <div class="page-content">
                <!-- Header Section -->
                <div class="header-section">
                    <div class="company-header">
                        <p class="company-name">RUBYSHOP</p>
                        @if(!empty($receipt_details->display_name))
                            <p class="company-thai">{{ $receipt_details->display_name }}</p>
                        @endif
                    </div>
                    
                    <div class="quotation-title">
                        <p class="quotation-thai">ใบเสนอราคา</p>
                        <p class="quotation-eng">QUOTATION</p>
                        <p>หน้าที่ {{ $page_num + 2 }}/{{ $total_pages }}</p>
                    </div>
                    
                    <div style="clear: both; height: 60px;"></div>
                    
                    @if(!empty($receipt_details->address))
                        <div style="text-align: center; margin: 5px 0; font-size: 13px;">
                            {!! $receipt_details->address !!}
                        </div>
                    @endif
                    
                    <div class="red-band">
                        @if(!empty($receipt_details->contact))
                            {{ $receipt_details->contact }}
                        @endif
                    </div>
                    
                    <div class="gray-band">
                        @if(!empty($receipt_details->tax_info1))
                            COMPANY ID: {{ $receipt_details->tax_info1 }}
                        @endif
                        @if(!empty($receipt_details->website))
                            {{ $receipt_details->website }}
                        @endif
                    </div>
                </div>

                <!-- Document Information -->
                <div class="doc-info">
                    เลขที่เอกสาร : {{ $receipt_details->invoice_no }}
                    &nbsp;&nbsp;&nbsp; วันที่ : {{ $receipt_details->invoice_date }}
                    &nbsp;&nbsp;&nbsp; เงื่อนไขการชำระเงิน :
                </div>

                <!-- Customer and Sales Information -->
                <div class="info-section clearfix">
                    <div class="customer-section">
                        <div class="section-title">ข้อมูลลูกค้า</div>
                        
                        <div style="margin-bottom: 5px;">
                            <span class="info-label">ชื่อบริษัท :</span>
                            <span class="info-value">
                                @if(!empty($receipt_details->customer_info))
                                    @php
                                        $customer_info = strip_tags($receipt_details->customer_info);
                                        $lines = explode("\n", $customer_info);
                                        $company_name = !empty($lines[1]) ? trim($lines[1]) : '';
                                    @endphp
                                    {{ $company_name }}
                                @endif
                            </span>
                        </div>
                        
                        <div style="margin-bottom: 5px;">
                            <span class="info-label">ที่อยู่ :</span>
                            <span class="info-value">
                                @if(!empty($receipt_details->customer_info))
                                    @php
                                        $customer_info = strip_tags($receipt_details->customer_info);
                                        $lines = explode("\n", $customer_info);
                                        // Extract address lines (skip first line which is company name)
                                        $address_lines = [];
                                        for($i = 1; $i < count($lines); $i++) {
                                            $line = trim($lines[$i]);
                                            // Skip lines that contain phone/tel information
                                            if(!empty($line) && 
                                               strpos($line, 'Tel:') === false && 
                                               strpos($line, 'Phone:') === false && 
                                               strpos($line, 'โทร:') === false &&
                                               strpos($line, 'เบอร์') === false) {
                                                $address_lines[] = $line;
                                            }
                                        }
                                        $address = implode(' ', $address_lines);
                                    @endphp
                                    {{ $address ?: '-' }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        
                        <div style="margin-bottom: 5px;">
                            <span class="info-label">เลขประจำตัวผู้เสียภาษี :</span>
                            <span class="info-value">
                                @if(!empty($receipt_details->customer_tax_number))
                                    {{ $receipt_details->customer_tax_number }}
                                @endif
                            </span>
                        </div>
                        
                        <div style="margin-bottom: 5px;">
                            <span class="info-label">เบอร์โทร :</span>
                            <span class="info-value">
                                @if(!empty($receipt_details->customer_info))
                                    @php
                                        $customer_lines = explode("\n", strip_tags($receipt_details->customer_info));
                                        $phone = '';
                                        foreach($customer_lines as $line) {
                                            if(strpos($line, 'Tel:') !== false || strpos($line, 'Phone:') !== false || strpos($line, 'โทร:') !== false) {
                                                $phone = trim(str_replace(['Tel:', 'Phone:', 'โทร:'], '', $line));
                                                break;
                                            }
                                        }
                                    @endphp
                                    {{ $phone ?: '-' }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="sales-section">
                        <div class="section-title">ข้อมูลผู้เสนอราคา</div>
                        
                        <div style="margin-bottom: 5px;">
                            <span class="info-label">พนักงาน :</span>
                            <span class="info-value">
                                @if(!empty($receipt_details->sales_person))
                                    {{ $receipt_details->sales_person }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        
                        <div style="margin-bottom: 5px;">
                            <span class="info-label">เบอร์โทร :</span>
                            <span class="info-value">-</span>
                        </div>
                        
                        <div style="margin-bottom: 5px;">
                            <span class="info-label">อีเมล์ :</span>
                            <span class="info-value">-</span>
                        </div>
                        
                        <div style="margin-bottom: 5px;">
                            <span class="info-label">Line :</span>
                            <span class="info-value">-</span>
                        </div>
                    </div>
                </div>

                <!-- Products Section -->
                <div class="products-section">
                    <div class="products-title">
                        PRODUCTS AND SERVICES DESCRIPTION / สินค้าและบริการ (ต่อ)
                    </div>
                    
                    <table>
                        <tr class="table-header">
                            <td width="45%">Description of Services and Goods</td>
                            <td width="15%" class="text-center">Quantity</td>
                            <td width="20%" class="text-center">Price Per Unit<br />(Baht)</td>
                            <td width="20%" class="text-center">Amount</td>
                        </tr>

                        @for($i = 0; $i < $products_per_page; $i++)
                            <tr>
                                @if(isset($chunk[$i]))
                                    @php $line = $chunk[$i]; @endphp
                                    <td>
                                        {{ $current_index + $i + 1 }} | 
                                        {{ $line['name'] }} {{ $line['product_variation'] }} {{ $line['variation'] }}
                                        @if(!empty($line['sub_sku']))
                                            <br>{{ $line['sub_sku'] }}
                                        @endif
                                        @if(!empty($line['product_description']))
                                            <br>{!! $line['product_description'] !!}
                                        @endif
                                        @if(!empty($line['sell_line_note']))
                                            <br>{!! $line['sell_line_note'] !!}
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $line['quantity'] }} {{ $line['units'] }}</td>
                                    <td class="text-center">{{ $line['unit_price_before_discount'] }}</td>
                                    <td class="text-right">{{ $line['line_total'] }}</td>
                                @else
                                    <td>&nbsp;</td>
                                    <td class="text-center">&nbsp;</td>
                                    <td class="text-center">&nbsp;</td>
                                    <td class="text-right">&nbsp;</td>
                                @endif
                            </tr>
                        @endfor
                    </table>
                </div>

                @if($loop->last)
                    <!-- Summary Section (only on last page) -->
                    <div class="summary-table">
                        <table>
                            <tr>
                                <td class="text-right">Subtotal:</td>
                                <td class="text-right">{{ $receipt_details->subtotal }}</td>
                            </tr>
                            @if(!empty($receipt_details->discount))
                                <tr>
                                    <td class="text-right">Discount:</td>
                                    <td class="text-right">{{ $receipt_details->discount }}</td>
                                </tr>
                            @endif
                            @if(!empty($receipt_details->tax))
                                <tr>
                                    <td class="text-right">Tax:</td>
                                    <td class="text-right">{{ $receipt_details->tax }}</td>
                                </tr>
                            @endif
                            <tr class="realtotal">
                                <td class="text-right">Total:</td>
                                <td class="text-right">{{ $receipt_details->total }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Signature Section (only on last page) -->
                    <div class="signature-section clearfix">
                        <div class="signature-box">
                            <div class="signature-title">ผู้รับสินค้า<br />Received By</div>
                            <div style="font-size: 9px; line-height: 1.1; margin-left: 20px;" class="terms2 latin">
                                ได้รับสินค้าครบตามรายการพร้อม<br />
                                ได้รับใบกำกับภาษีเรียบร้อยแล้ว<br />
                                โปรดลงลายมือชื่อด้วยตัวบรรจง
                            </div>
                            <div class="signature-line">
                                ผู้รับสินค้า/Received By<br />
                                วันที่/Date
                            </div>
                        </div>

                    <div class="signature-box">
    <div class="signature-title">
        เงื่อนไขข้อตกลง<br />Terms and Conditions
    </div>

    <div style="font-size: 10px; line-height: 1.8; letter-spacing: 0.5px;">
        <ul style="padding-left: 15px; margin: 0;">
            <li>ได้รับสินค้าตามรายการข้างต้นนี้ถูกต้อง และยินยอมในเงื่อนไขตามเอกสารนี้</li>
            <li>กรุณาสั่งจ่ายเช็คขีดคร่อมในนาม {{ $receipt_details->display_name ?: 'ห้างหุ้นส่วนจำกัดรูบี้ช๊อป' }}</li>
            <li>บริษัทจะคิดอัตราดอกเบี้ย 1.5% ต่อเดือนสำหรับใบแจ้งหนี้ที่ไม่ชำระตามกำหนด</li>
        </ul>
    </div>
</div>


                        <div class="signature-box">
                            <div class="signature-title">ผู้ส่งสินค้า<br />Delivery By</div>
                            <div class="signature-line">
                                ผู้ตรวจสอบสินค้า/QC1<br />
                                ผู้ส่งสินค้า/Delivered By
                                
                            </div>
                        </div>

                        <div class="signature-box">
                            <div class="signature-title approve-box">Approve By/ผู้มีอํานาจอนุมัติ</div>
                            <div class="signature-line">
                                ลายเซ็น/Signature5555
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Empty Summary Section for non-last pages -->
                    <div class="summary-table">
                        <table>
                            <tr>
                                <td class="text-right">Subtotal:</td>
                                <td class="text-right"></td>
                            </tr>
                            @if(!empty($receipt_details->discount))
                                <tr>
                                    <td class="text-right">Discount:</td>
                                    <td class="text-right"></td>
                                </tr>
                            @endif
                            @if(!empty($receipt_details->tax))
                                <tr>
                                    <td class="text-right">Tax:</td>
                                    <td class="text-right"></td>
                                </tr>
                            @endif
                            <tr class="total-row">
                                <td class="text-right">Total:</td>
                                <td class="text-right"></td>
                            </tr>
                        </table>
                    </div>

                    <!-- Empty Signature Section for non-last pages -->
                    <div class="signature-section clearfix">
                        <div class="signature-box">
                            <div class="signature-title">ผู้รับสินค้า<br />Received By</div>
                            <div style="font-size: 9px; line-height: 1.1;">
                                ได้รับสินค้าครบตามรายการพร้อม<br />
                                ได้รับใบกำกับภาษีเรียบร้อยแล้ว<br />
                                โปรดลงลายมือชื่อด้วยตัวบรรจง
                            </div>
                            <div class="signature-line">
                                ผู้รับสินค้า/Received By<br />
                                วันที่/Date
                            </div>
                        </div>

                        <div class="signature-box">
                            <div class="signature-title">เงื่อนไขข้อมตกลง<br />Terms and Conditions</div>
                            <div style="font-size: 8px; line-height: 1.0;">
                                <ul style="margin: 3px 0; padding-left: 12px;">
                                    <li>ได้รับสินค้าตามรานการข้างต้นนี้ถูกต้อง และยินยอมในเงื่อนไขตามเอกสารนี้</li>
                                    <li>กรุณาสั่งจ่ายเช็คขีดคร่อมในนาม {{ $receipt_details->display_name ?: 'ห้างหุ้นส่วนจำกัดรูบี้ช๊อป' }}</li>
                                    <li>บริษัทจะคิดอัตราดอกเบี้ย 1.5% ต่อเดือนสำหรับใบแจ้งหนี้ที่ไม่ชำระตามกำหนด</li>
                                </ul>
                            </div>
                        </div>

                        <div class="signature-box">
                            <div class="signature-title">ผู้ส่งสินค้า<br />Delivery By</div>
                            <div class="signature-line">
                                ผู้ตรวจสอบสินค้า/QC1<br />
                            .............................
                                ผู้ส่งสินค้า/Delivered By
                            </div>
                        </div>

                        <div class="signature-box signature-box-approve">
                            <div class="signature-title approve-box">Approve By/ผู้มีอํานาจอนุมัติ</div>
                            <div class="signature-line">
                                
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            @php
                $current_index += count($chunk);
            @endphp
        @endforeach
    @endif
</body>
</html>
        
        .quotation-title {
            text-align: right;
            margin-top: -60px;
            margin-right: 50px;
        }
        
        .quotation-thai {
            font-size: 26px;
            font-weight: bold;
            color: #000;
            margin: 0;
        }
        
        .quotation-eng {
            font-size: 16px;
            font-weight: bold;
            color: #000;
            margin: 0;
        }
        
        .red-band {
            background-color: #dd242f;
            height: 24px;
            color: #FFF;
            text-align: center;
            font-size: 13px;
            line-height: 24px;
            margin: 2px 0 0 0;
        }
        
        .gray-band {
            background-color: #676767;
            height: 24px;
            color: #FFF;
            text-align: center;
            font-size: 13px;
            line-height: 24px;
            margin: 0 0 5px 0;
        }
        
        .doc-info {
            background: #e9e9e9;
            padding: 8px 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .info-section {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .customer-section {
            float: left;
            width: 45%;
            padding: 8px;
            border: 2px solid #ccc;
            margin-right: 5%;
            height: 100px;
        }
        
        .sales-section {
            float: right;
            width: 45%;
            padding: 8px;
            border: 2px solid #ccc;
            height: 100px;
        }
        
        .section-title {
            background: #797b7d;
            color: white;
            padding: 5px;
            text-align: center;
            font-weight: bold;
            margin: -8px -8px 8px -8px;
            font-size: 12px;
        }
        
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 100px;
            text-align: right;
            margin-right: 8px;
            font-size: 12px;
        }
        
        .products-section {
            clear: both;
            margin-top: 15px;
        }
        
        .products-title {
            background: #797b7d;
            color: white;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        .table-header {
            background: #dc8285;
            color: #FFF;
            font-weight: bold;
        }
        
        table td, table th {
            border: 1px solid #EAEAEA;
            padding: 4px;
            text-align: left;
            font-size: 12px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .summary-table {
            float: right;
            width: 300px;
            margin-top: 5px;
        }
        
        .summary-table td {
            padding: 3px;
            font-size: 12px;
        }
        
        .total-row {
            background: #dc8285;
            color: #fff;
            font-weight: bold;
        }
        
        .signature-section {
            clear: both;
            margin-top: 20px;
            padding-top: 10px;
        }
        
        .signature-box {
            float: left;
            width: 22%;
            margin-right: 3%;
            border: 1px solid #ccc;
            padding: 8px;
            height: 80px;
            font-size: 10px;
        }
        
        .signature-title {
            background: #ececec;
            text-align: center;
            padding: 5px;
            font-weight: bold;
            margin: -8px -8px 8px -8px;
            font-size: 11px;
        }
        
        .approve-box {
            background: #F00;
            color: #fff;
        }
        
        .signature-line {
            border-bottom: 1px dotted #000;
            margin-top: 20px;
            text-align: center;
            padding-top: 3px;
            font-size: 9px;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .info-value {
            font-size: 12px;
        }
        .company-thai{
            position: absolute;
            left: 0px;
            top: 20px;
        }
    </style>
</head>
<body>
    @php
        $products_per_page = 5;
        $total_products = count($receipt_details->lines);
        $total_pages = $total_products > $products_per_page ? ceil($total_products / $products_per_page) : 1;
        $is_multi_page = $total_products > $products_per_page;
    @endphp

    <!-- First Page -->
    <div class="page-content">
        <!-- Header Section -->
        <div class="header-section">
            <div class="company-header">
                
                <p class="company-name">RUBYSHOP</p>
                <p class="company-eng">ห้างหุ้นส่วนจำกัดรูบี้ช๊อป</p>
                @if(!empty($receipt_details->display_name))
                    <p class="company-thai">{{ $receipt_details->display_name }}</p>
                @endif
            </div>
            
            <div class="quotation-title">
                <p class="quotation-thai">ใบเสนอราคา</p>
                <p class="quotation-eng">QUOTATION</p>
                <p>หน้าที่ 1/{{ $total_pages }}</p>
            </div>
            
            <div style="clear: both;"></div>
            
            @if(!empty($receipt_details->address))
                <div style="text-align: center; margin: 5px 0; font-size: 13px;">
                    {!! $receipt_details->address !!}
                </div>
            @endif
            
            <div class="red-band">
                @if(!empty($receipt_details->contact))
                    {{ $receipt_details->contact }}
                @endif
            </div>
            
            <div class="gray-band">
                @if(!empty($receipt_details->tax_info1))
                    COMPANY ID: {{ $receipt_details->tax_info1 }}
                @endif
                @if(!empty($receipt_details->website))
                    {{ $receipt_details->website }}
                @endif
            </div>
        </div>

        <!-- Document Information -->
        <div class="doc-info">
            เลขที่เอกสาร : {{ $receipt_details->invoice_no }}
            &nbsp;&nbsp;&nbsp; วันที่ : {{ $receipt_details->invoice_date }}
            &nbsp;&nbsp;&nbsp; เงื่อนไขการชำระเงิน :
        </div>

        <!-- Customer and Sales Information -->
        <div class="info-section clearfix">
            <div class="customer-section">
                <div class="section-title">ข้อมูลลูกค้า</div>
                
                <div style="margin-bottom: 5px;">
                    <span class="info-label">ชื่อบริษัท :</span>
                    <span class="info-value">
                        @if(!empty($receipt_details->customer_info))
                            @php
                                $customer_info = strip_tags($receipt_details->customer_info);
                                $lines = explode("\n", $customer_info);
                                $company_name = !empty($lines[0]) ? trim($lines[0]) : '';
                            @endphp
                            {{ $company_name }}  
                        @endif
                    </span>
                </div>
                
                <div style="margin-bottom: 5px;">
                    <span class="info-label">ที่อยู่ :</span>
                    <span class="info-value">
                        @if(!empty($receipt_details->customer_info))
                            @php
                                $customer_info = strip_tags($receipt_details->customer_info);
                                $lines = explode("\n", $customer_info);
                                // Extract address lines (skip first line which is company name)
                                $address_lines = [];
                                for($i = 1; $i < count($lines); $i++) {
                                    $line = trim($lines[$i]);
                                    // Skip lines that contain phone/tel information
                                    if(!empty($line) && 
                                       strpos($line, 'Tel:') === false && 
                                       strpos($line, 'Phone:') === false && 
                                       strpos($line, 'โทร:') === false &&
                                       strpos($line, 'เบอร์') === false) {
                                        $address_lines[] = $line;
                                    }
                                }
                                $address = implode(' ', $address_lines);
                            @endphp
                            {{ $address ?: '-' }}
                        @else
                            -
                        @endif
                    </span>
                </div>
              

                <div style="margin-bottom: 5px;">
                    <span class="info-label">เลขประจำตัวผู้เสียภาษี :</span>
                    <span class="info-value">
                        @if(!empty($receipt_details->customer_tax_number))
                            {{ $receipt_details->customer_tax_number }}
                        @endif
                    </span>
                </div>
                
                <div style="margin-bottom: 5px;">
                    <span class="info-label">เบอร์โทร :</span>
                    <span class="info-value">
                        @if(!empty($receipt_details->customer_info))
                            @php
                                $customer_lines = explode("\n", strip_tags($receipt_details->customer_info));
                                $phone = '';
                                foreach($customer_lines as $line) {
                                    if(strpos($line, 'Tel:') !== false || strpos($line, 'Phone:') !== false || strpos($line, 'โทร:') !== false) {
                                        $phone = trim(str_replace(['Tel:', 'Phone:', 'โทร:'], '', $line));
                                        break;
                                    }
                                }
                            @endphp
                            {{ $phone ?: '-' }}
                        @else
                            -
                        @endif
                    </span>
                </div>
            </div>

            <div class="sales-section">
                <div class="section-title">ข้อมูลผู้เสนอราคา</div>
                
                <div style="margin-bottom: 5px;">
                    <span class="info-label">พนักงาน :</span>
                    <span class="info-value">
                        @if(!empty($receipt_details->sales_person))
                            {{ $receipt_details->sales_person }}
                        @else
                            -
                        @endif
                    </span>
                </div>
                
                <div style="margin-bottom: 5px;">
                    <span class="info-label">เบอร์โทร :</span>
                    <span class="info-value">-</span>
                </div>
                
                <div style="margin-bottom: 5px;">
                    <span class="info-label">อีเมล์ :</span>
                    <span class="info-value">-</span>
                </div>
                
                <div style="margin-bottom: 5px;">
                    <span class="info-label">Line :</span>
                    <span class="info-value">-</span>
                </div>
            </div>
        </div>

        <!-- Products Section -->
        <div class="products-section">
            <div class="products-title">
                PRODUCTS AND SERVICES DESCRIPTION / สินค้าและบริการ
            </div>
            
            <table>
                <tr class="table-header">
                    <td width="45%">Description of Services and Goods</td>
                    <td width="15%" class="text-center">Quantity</td>
                    <td width="20%" class="text-center">Price Per Unit<br />(Baht)</td>
                    <td width="20%" class="text-center">Amount</td>
                </tr>

                @php
                    $first_page_products = array_slice($receipt_details->lines, 0, $products_per_page);
                @endphp

                @forelse($first_page_products as $index => $line)
                    <tr>
                        <td>
                            {{ $index + 1 }} | 
                            {{ $line['name'] }} {{ $line['product_variation'] }} {{ $line['variation'] }}
                            @if(!empty($line['sub_sku']))
                                <br>{{ $line['sub_sku'] }}
                            @endif
                            @if(!empty($line['product_description']))
                                <br>{!! $line['product_description'] !!}
                            @endif
                            @if(!empty($line['sell_line_note']))
                                <br>{!! $line['sell_line_note'] !!}
                            @endif
                        </td>
                        <td class="text-center">{{ $line['quantity'] }} {{ $line['units'] }}</td>
                        <td class="text-center">{{ $line['unit_price_before_discount'] }}</td>
                        <td class="text-right">{{ $line['line_total'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">ไม่มีรายการสินค้า</td>
                    </tr>
                @endforelse
            </table>
        </div>

        <!-- Summary Section -->
        <div class="summary-table">
            <table>
                <tr>
                    <td class="text-right">Subtotal:</td>
                    <td class="text-right">{{ $is_multi_page ? '' : $receipt_details->subtotal }}</td>
                </tr>
                @if(!empty($receipt_details->discount))
                    <tr>
                        <td class="text-right">Discount:</td>
                        <td class="text-right">{{ $is_multi_page ? '' : $receipt_details->discount }}</td>
                    </tr>
                @endif
                @if(!empty($receipt_details->tax))
                    <tr>
                        <td class="text-right">Tax:</td>
                        <td class="text-right">{{ $is_multi_page ? '' : $receipt_details->tax }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td class="text-right">Total:</td>
                    <td class="text-right">{{ $is_multi_page ? '' : $receipt_details->total }}</td>
                </tr>
            </table>
        </div>

        @if(!$is_multi_page)
            <!-- Signature Section (only on single page) -->
            <div class="signature-section clearfix">
                <div class="signature-box">
                    <div class="signature-title">ผู้รับสินค้า<br />Received By</div>
                    <div style="font-size: 9px; line-height: 1.1;">
                        ได้รับสินค้าครบตามรายการพร้อม<br />
                        ได้รับใบกำกับภาษีเรียบร้อยแล้ว<br />
                        โปรดลงลายมือชื่อด้วยตัวบรรจง
                    </div>
                    <div class="signature-line">
                        ผู้รับสินค้า/Received By<br />
                        วันที่/Date
                    </div>
                </div>

                <div class="signature-box">
                    <div class="signature-title">เงื่อนไขข้อมตกลง<br />Terms and Conditions</div>
                    <div style="font-size: 8px; line-height: 1.0;">
                        <ul style="margin: 3px 0; padding-left: 12px;">
                            <li>ได้รับสินค้าตามรานการข้างต้นนี้ถูกต้อง และยินยอมในเงื่อนไขตามเอกสารนี้</li>
                            <li>กรุณาสั่งจ่ายเช็คขีดคร่อมในนาม {{ $receipt_details->display_name ?: 'ห้างหุ้นส่วนจำกัดรูบี้ช๊อป' }}</li>
                            <li>บริษัทจะคิดอัตราดอกเบี้ย 1.5% ต่อเดือนสำหรับใบแจ้งหนี้ที่ไม่ชำระตามกำหนด</li>
                        </ul>
                    </div>
                </div>

                <div class="signature-box">
                    <div class="signature-title">ผู้ส่งสินค้า<br />Delivery By</div>
                    <div class="signature-line">
                        ผู้ตรวจสอบสินค้า/QC1<br />
                        ผู้ส่งสินค้า/Delivered By
                    </div>
                </div>

                <div class="signature-box">
                    <div class="signature-title approve-box">Approve By/ผู้มีอํานาจอนุมัติ</div>
                    <div class="signature-line">
                        ลายเซ็น/Signature
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if($is_multi_page)
        @php
            $remaining_products = array_slice($receipt_details->lines, $products_per_page);
            $chunks = array_chunk($remaining_products, $products_per_page);
            $current_index = $products_per_page;
        @endphp

        @foreach($chunks as $page_num => $chunk)
            <div class="page-break"></div>
            <div class="page-content">
                <!-- Header Section -->
                <div class="header-section">
                    <div class="company-header">
                        <p class="company-name">RUBYSHOP</p>
                        
                        @if(!empty($receipt_details->display_name))
                            <p class="company-thai">{{ $receipt_details->display_name }}</p>
                        @endif
                    </div>
                    
                    <div class="quotation-title">
                        <p class="quotation-thai">ใบเสนอราคา</p>
                        <p class="quotation-eng">QUOTATION</p>
                        <p>หน้าที่ {{ $page_num + 2 }}/{{ $total_pages }}</p>
                    </div>
                    
                    <div style="clear: both;"></div>
                    
                    @if(!empty($receipt_details->address))
                        <div style="text-align: center; margin: 5px 0; font-size: 13px;">
                            {!! $receipt_details->address !!}
                        </div>
                    @endif
                    
                    <div class="red-band">
                        @if(!empty($receipt_details->contact))
                            {{ $receipt_details->contact }}
                        @endif
                    </div>
                    
                    <div class="gray-band">
                        @if(!empty($receipt_details->tax_info1))
                            COMPANY ID: {{ $receipt_details->tax_info1 }}
                        @endif
                        @if(!empty($receipt_details->website))
                            {{ $receipt_details->website }}
                        @endif
                    </div>
                </div>

                <!-- Document Information -->
                <div class="doc-info">
                    เลขที่เอกสาร : {{ $receipt_details->invoice_no }}
                    &nbsp;&nbsp;&nbsp; วันที่ : {{ $receipt_details->invoice_date }}
                    &nbsp;&nbsp;&nbsp; เงื่อนไขการชำระเงิน :
                </div>

                <!-- Customer and Sales Information -->
                <div class="info-section clearfix">
                    <div class="customer-section">
                        <div class="section-title">ข้อมูลลูกค้า</div>
                        
                        <div style="margin-bottom: 5px;">
                            <span class="info-label">ชื่อบริษัท :</span>
                            <span class="info-value">
                                @if(!empty($receipt_details->customer_info))
                                    @php
                                        $customer_info = strip_tags($receipt_details->customer_info);
                                        $lines = explode("\n", $customer_info);
                                        $company_name = !empty($lines[1]) ? trim($lines[1]) : '';
                                    @endphp
                                    {{ $company_name }}
                                @endif
                            </span>
                        </div>
                        
                        <div style="margin-bottom: 5px;">
                            <span class="info-label">ที่อยู่ :</span>
                            <span class="info-value">
                                @if(!empty($receipt_details->customer_info))
                                    @php
                                        $customer_info = strip_tags($receipt_details->customer_info);
                                        $lines = explode("\n", $customer_info);
                                        // Extract address lines (skip first line which is company name)
                                        $address_lines = [];
                                        for($i = 1; $i < count($lines); $i++) {
                                            $line = trim($lines[$i]);
                                            // Skip lines that contain phone/tel information
                                            if(!empty($line) && 
                                               strpos($line, 'Tel:') === false && 
                                               strpos($line, 'Phone:') === false && 
                                               strpos($line, 'โทร:') === false &&
                                               strpos($line, 'เบอร์') === false) {
                                                $address_lines[] = $line;
                                            }
                                        }
                                        $address = implode(' ', $address_lines);
                                    @endphp
                                    {{ $address ?: '-' }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        
                        <div style="margin-bottom: 5px;">
                            <span class="info-label">เลขประจำตัวผู้เสียภาษี :</span>
                            <span class="info-value">
                                @if(!empty($receipt_details->customer_tax_number))
                                    {{ $receipt_details->customer_tax_number }}
                                @endif
                            </span>
                        </div>
                        
                        <div style="margin-bottom: 5px;">
                            <span class="info-label">เบอร์โทร :</span>
                            <span class="info-value">
                                @if(!empty($receipt_details->customer_info))
                                    @php
                                        $customer_lines = explode("\n", strip_tags($receipt_details->customer_info));
                                        $phone = '';
                                        foreach($customer_lines as $line) {
                                            if(strpos($line, 'Tel:') !== false || strpos($line, 'Phone:') !== false || strpos($line, 'โทร:') !== false) {
                                                $phone = trim(str_replace(['Tel:', 'Phone:', 'โทร:'], '', $line));
                                                break;
                                            }
                                        }
                                    @endphp
                                    {{ $phone ?: '-' }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="sales-section">
                        <div class="section-title">ข้อมูลผู้เสนอราคา</div>
                        
                        <div style="margin-bottom: 5px;">
                            <span class="info-label">พนักงาน :</span>
                            <span class="info-value">
                                @if(!empty($receipt_details->sales_person))
                                    {{ $receipt_details->sales_person }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        
                        <div style="margin-bottom: 5px;">
                            <span class="info-label">เบอร์โทร :</span>
                            <span class="info-value">-</span>
                        </div>
                        
                        <div style="margin-bottom: 5px;">
                            <span class="info-label">อีเมล์ :</span>
                            <span class="info-value">-</span>
                        </div>
                        
                        <div style="margin-bottom: 5px;">
                            <span class="info-label">Line :</span>
                            <span class="info-value">-</span>
                        </div>
                    </div>
                </div>

                <!-- Products Section -->
                <div class="products-section">
                    <div class="products-title">
                        PRODUCTS AND SERVICES DESCRIPTION / สินค้าและบริการ (ต่อ)
                    </div>
                    
                    <table>
                        <tr class="table-header">
                            <td width="45%">Description of Services and Goods</td>
                            <td width="15%" class="text-center">Quantity</td>
                            <td width="20%" class="text-center">Price Per Unit<br />(Baht)</td>
                            <td width="20%" class="text-center">Amount</td>
                        </tr>

                        @foreach($chunk as $index => $line)
                            <tr>
                                <td>
                                    {{ $current_index + $index + 1 }} | 
                                    {{ $line['name'] }} {{ $line['product_variation'] }} {{ $line['variation'] }}
                                    @if(!empty($line['sub_sku']))
                                        <br>{{ $line['sub_sku'] }}
                                    @endif
                                    @if(!empty($line['product_description']))
                                        <br>{!! $line['product_description'] !!}
                                    @endif
                                    @if(!empty($line['sell_line_note']))
                                        <br>{!! $line['sell_line_note'] !!}
                                    @endif
                                </td>
                                <td class="text-center">{{ $line['quantity'] }} {{ $line['units'] }}</td>
                                <td class="text-center">{{ $line['unit_price_before_discount'] }}</td>
                                <td class="text-right">{{ $line['line_total'] }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                @if($loop->last)
                    <!-- Summary Section (only on last page) -->
                    <div class="summary-table">
                        <table>
                            <tr>
                                <td class="text-right">Subtotal:</td>
                                <td class="text-right">{{ $receipt_details->subtotal }}</td>
                            </tr>
                            @if(!empty($receipt_details->discount))
                                <tr>
                                    <td class="text-right">Discount:</td>
                                    <td class="text-right">{{ $receipt_details->discount }}</td>
                                </tr>
                            @endif
                            @if(!empty($receipt_details->tax))
                                <tr>
                                    <td class="text-right">Tax:</td>
                                    <td class="text-right">{{ $receipt_details->tax }}</td>
                                </tr>
                            @endif
                            <tr class="total-row">
                                <td class="text-right">Total:</td>
                                <td class="text-right">{{ $receipt_details->total }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Signature Section (only on last page) -->
                    <div class="signature-section clearfix">
                        <div class="signature-box">
                            <div class="signature-title">ผู้รับสินค้า<br />Received By</div>
                            <div style="font-size: 9px; line-height: 1.1;">
                                ได้รับสินค้าครบตามรายการพร้อม<br />
                                ได้รับใบกำกับภาษีเรียบร้อยแล้ว<br />
                                โปรดลงลายมือชื่อด้วยตัวบรรจง
                            </div>
                            <div class="signature-line">
                                ผู้รับสินค้า/Received By<br />
                                วันที่/Date
                            </div>
                        </div>

                        <div class="signature-box">
                            <div class="signature-title">เงื่อนไขข้อมตกลง<br />Terms and Conditions</div>
                            <div style="font-size: 8px; line-height: 1.0;">
                                <ul style="margin: 3px 0; padding-left: 12px;">
                                    <li>ได้รับสินค้าตามรานการข้างต้นนี้ถูกต้อง และยินยอมในเงื่อนไขตามเอกสารนี้</li>
                                    <li>กรุณาสั่งจ่ายเช็คขีดคร่อมในนาม {{ $receipt_details->display_name ?: 'ห้างหุ้นส่วนจำกัดรูบี้ช๊อป' }}</li>
                                    <li>บริษัทจะคิดอัตราดอกเบี้ย 1.5% ต่อเดือนสำหรับใบแจ้งหนี้ที่ไม่ชำระตามกำหนด</li>
                                </ul>
                            </div>
                        </div>

                        <div class="signature-box">
                            <div class="signature-title">ผู้ส่งสินค้า<br />Delivery By</div>
                            <div class="signature-line">
                                ผู้ตรวจสอบสินค้า/QC1<br />
                                ผู้ส่งสินค้า/Delivered By
                            </div>
                        </div>

                        <div class="signature-box">
                            <div class="signature-title approve-box">Approve By/ผู้มีอํานาจอนุมัติ</div>
                            <div class="signature-line">
                                ลายเซ็น/Signature
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            @php
                $current_index += count($chunk);
            @endphp
        @endforeach
    @endif
</body>
    <!-- Header Section -->
    <div class="header-section">
        <div class="company-header">
            <p class="company-name">RUBYSHOP</p>
            @if(!empty($receipt_details->display_name))
                <p class="company-thai">{{ $receipt_details->display_name }}</p>
            @endif
        </div>
        
        <div class="quotation-title">
            <p class="quotation-thai">ใบเสนอราคา</p>
            <p class="quotation-eng">QUOTATION</p>
            <p>หน้าที่ 1/1</p>
        </div>
        
        <div style="clear: both;"></div>
        
        @if(!empty($receipt_details->address))
            <div style="text-align: center; margin: 10px 0; font-size: 15px;">
                {!! $receipt_details->address !!}
            </div>
        @endif
        
        <div class="red-band">
            @if(!empty($receipt_details->contact))
                {{ $receipt_details->contact }}
            @endif
        </div>
        
        <div class="gray-band">
            @if(!empty($receipt_details->tax_info1))
                COMPANY ID: {{ $receipt_details->tax_info1 }}
            @endif
            @if(!empty($receipt_details->website))
                {{ $receipt_details->website }}
            @endif
        </div>
    </div>

    <!-- Document Information -->
    <div class="doc-info">
        เลขที่เอกสาร : {{ $receipt_details->invoice_no }}
        &nbsp;&nbsp;&nbsp; วันที่ : {{ $receipt_details->invoice_date }}
        &nbsp;&nbsp;&nbsp; เงื่อนไขการชำระเงิน :
    </div>

    <!-- Customer and Sales Information -->
    <div class="info-section clearfix">
        <div class="customer-section">
            <div class="section-title">ข้อมูลลูกค้า</div>
            
            <div style="margin-bottom: 8px;">
                <span class="info-label">ชื่อบริษัท :</span>
                @if(!empty($receipt_details->customer_info))
                    @php
                        $customer_info = strip_tags($receipt_details->customer_info);
                        $lines = explode("\n", $customer_info);
                        $company_name = !empty($lines[0]) ? trim($lines[0]) : '';
                    @endphp
                    {{ $company_name }}
                @endif
            </div>
            
            <div style="margin-bottom: 8px;">
                <span class="info-label">ที่อยู่ :</span>
                @if(!empty($receipt_details->customer_info))
                    @php
                        $customer_info = strip_tags($receipt_details->customer_info);
                        $lines = explode("\n", $customer_info);
                        // Extract address lines (skip first line which is company name)
                        $address_lines = [];
                        for($i = 1; $i < count($lines); $i++) {
                            $line = trim($lines[$i]);
                            // Skip lines that contain phone/tel information
                            if(!empty($line) && 
                               strpos($line, 'Tel:') === false && 
                               strpos($line, 'Phone:') === false && 
                               strpos($line, 'โทร:') === false &&
                               strpos($line, 'เบอร์') === false) {
                                $address_lines[] = $line;
                            }
                        }
                        $address = implode(' ', $address_lines);
                    @endphp
                    {{ $address ?: '-' }}
                @else
                    -
                @endif
            </div>
            
            <div style="margin-bottom: 8px;">
                <span class="info-label">เลขประจำตัวผู้เสียภาษี :</span>
                @if(!empty($receipt_details->customer_tax_number))
                    {{ $receipt_details->customer_tax_number }}
                @endif
            </div>
            
            <div style="margin-bottom: 8px;">
                <span class="info-label">ที่อยู่ :</span>
                @if(!empty($receipt_details->customer_info))
                    @php
                        $customer_info = strip_tags($receipt_details->customer_info);
                        $lines = explode("\n", $customer_info);
                        $address_lines = [];
                        foreach($lines as $line) {
                            $line = trim($line);
                            if(!empty($line) && 
                               strpos($line, 'Tel:') === false && 
                               strpos($line, 'Phone:') === false && 
                               strpos($line, 'โทร:') === false &&
                               strpos($line, 'Email:') === false &&
                               strpos($line, 'อีเมล:') === false) {
                                $address_lines[] = $line;
                            }
                        }
                        echo implode('<br>', array_slice($address_lines, 1)); // Skip company name
                    @endphp
                @endif
            </div>
            
            <div style="margin-bottom: 8px;">
                <span class="info-label">เบอร์โทร :</span>
                @if(!empty($receipt_details->customer_info))
                    @php
                        $customer_lines = explode("\n", strip_tags($receipt_details->customer_info));
                        $phone = '';
                        foreach($customer_lines as $line) {
                            if(strpos($line, 'Tel:') !== false || strpos($line, 'Phone:') !== false || strpos($line, 'โทร:') !== false) {
                                $phone = trim(str_replace(['Tel:', 'Phone:', 'โทร:'], '', $line));
                                break;
                            }
                        }
                    @endphp
                    {{ $phone ?: '-' }}
                @else
                    -
                @endif
            </div>
        </div>

        <div class="sales-section">
            <div class="section-title">ข้อมูลผู้เสนอราคา</div>
            
            <div style="margin-bottom: 8px;">
                <span class="info-label">พนักงาน :</span>
                @if(!empty($receipt_details->sales_person))
                    {{ $receipt_details->sales_person }}
                @else
                    -
                @endif
            </div>
            
            <div style="margin-bottom: 8px;">
                <span class="info-label">เบอร์โทร :</span>
                -
            </div>
            
            <div style="margin-bottom: 8px;">
                <span class="info-label">อีเมล์ :</span>
                -
            </div>
            
            <div style="margin-bottom: 8px;">
                <span class="info-label">Line :</span>
                -
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <div class="products-section">
        <div class="products-title">
            PRODUCTS AND SERVICES DESCRIPTION / สินค้าและบริการ
        </div>
        
        <table>
            <tr class="table-header">
                <td width="45%">Description of Services and Goods</td>
                <td width="15%" class="text-center">Quantity</td>
                <td width="20%" class="text-center">Price Per Unit<br />(Baht)</td>
                <td width="20%" class="text-center">Amount</td>
            </tr>

            @forelse($receipt_details->lines as $index => $line)
                <tr>
                    <td>
                        {{ $index + 1 }} | 
                        {{ $line['name'] }} {{ $line['product_variation'] }} {{ $line['variation'] }}
                        @if(!empty($line['sub_sku']))
                            <br>{{ $line['sub_sku'] }}
                        @endif
                        @if(!empty($line['product_description']))
                            <br>{!! $line['product_description'] !!}
                        @endif
                        @if(!empty($line['sell_line_note']))
                            <br>{!! $line['sell_line_note'] !!}
                        @endif
                    </td>
                    <td class="text-center">{{ $line['quantity'] }} {{ $line['units'] }}</td>
                    <td class="text-center">{{ $line['unit_price_before_discount'] }}</td>
                    <td class="text-right">{{ $line['line_total'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">ไม่มีรายการสินค้า</td>
                </tr>
            @endforelse
        </table>
    </div>

    <!-- Summary Section -->
    <div class="summary-table">
        <table>
            <tr>
                <td class="text-right">Subtotal:</td>
                <td class="text-right">{{ $receipt_details->subtotal }}</td>
            </tr>
            @if(!empty($receipt_details->discount))
                <tr>
                    <td class="text-right">Discount:</td>
                    <td class="text-right">{{ $receipt_details->discount }}</td>
                </tr>
            @endif
            @if(!empty($receipt_details->tax))
                <tr>
                    <td class="text-right">Tax:</td>
                    <td class="text-right">{{ $receipt_details->tax }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td class="text-right">Total:</td>
                <td class="text-right">{{ $receipt_details->total }}</td>
            </tr>
        </table>
    </div>

    <!-- Signature Section -->
    <div class="signature-section clearfix">
        <div class="signature-box">
            <div class="signature-title">ผู้รับสินค้า<br />Received By</div>
            <div style="font-size: 12px; line-height: 1.2;">
                ได้รับสินค้าครบตามรายการพร้อม<br />
                ได้รับใบกำกับภาษีเรียบร้อยแล้ว<br />
                โปรดลงลายมือชื่อด้วยตัวบรรจง
            </div>
            <div class="signature-line">
                ผู้รับสินค้า/Received By<br />
                วันที่/Date
            </div>
        </div>

        <div class="signature-box">
            <div class="signature-title">เงื่อนไขข้อมตกลง<br />Terms and Conditions</div>
            <div style="font-size: 11px; line-height: 1.1;">
                <ul style="margin: 5px 0; padding-left: 15px;">
                    <li>ได้รับสินค้าตามรานการข้างต้นนี้ถูกต้อง และยินยอมในเงื่อนไขตามเอกสารนี้</li>
                    <li>กรุณาสั่งจ่ายเช็คขีดคร่อมในนาม {{ $receipt_details->display_name ?: 'ห้างหุ้นส่วนจำกัดรูบี้ช๊อป' }}</li>
                    <li>บริษัทจะคิดอัตราดอกเบี้ย 1.5% ต่อเดือนสำหรับใบแจ้งหนี้ที่ไม่ชำระตามกำหนด</li>
                </ul>
            </div>
        </div>

        <div class="signature-box">
            <div class="signature-title">ผู้ส่งสินค้า<br />Delivery By</div>
            <div class="signature-line">
                ผู้ตรวจสอบสินค้า/QC1<br />
                ผู้ส่งสินค้า/Delivered By
            </div>
        </div>

        <div class="signature-box">
            <div class="signature-title approve-box">Approve By/ผู้มีอํานาจอนุมัติ</div>
            <div class="signature-line">
                ลายเซ็น/Signature
            </div>
        </div>
    </div>



  

</body>
</html>
