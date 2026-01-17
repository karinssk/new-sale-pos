<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quotation PDF</title>
    <style>
        @page { 
            margin: 0px;
            padding: 0px;
            font-family: 'sarabun', sans-serif; 
            line-height: 1em;
        }
        
        body {
            font-family: 'sarabun', sans-serif;
            margin: 0;
            padding: 0;
            color: #000000;
        }
        
        .page {
            height: 1090px;
        }
        
        div, p, span { 
            padding: 0px; 
            margin: 0px;
        } 
        
        .labelstyle {
            -webkit-transform: rotate(270deg);
            -moz-transform: rotate(270deg);
            -o-transform: rotate(270deg);
            writing-mode: bt-lr;
            background: #797b7d;
            display: inline-block;
        }
        
        .tablel td { 
            padding: 5px;
        }
        
        .thead { 
            background: #dc8285; 
            color: #FFF; 
        }
        
        table td { 
            border: 1px solid #EAEAEA;
        }
        
        .dynamic-font { 
            font-size: inherit; 
            line-height: inherit; 
        }
        
        .header { 
            overflow: hidden; 
            height: 280px;
        }
        
        .tableprice td { 
            line-height: .6em; 
            padding: 3px 0px;
        }
        
        .pa { 
            position: absolute; 
            left: 0; 
            top: 0px;
        }
        
        .pr { 
            position: relative;
        }
        
        .header { 
            top: 0px; 
            left: 0px;
        }
        
        .bo { 
            border: 1px solid red; 
            padding: 0px;
        }
        
        .info { 
            width: 100%; 
            height: 200px; 
            top: 146px;
        }
        
        .customer_l { 
            width: 120px; 
            text-align: right; 
            line-height: 1.2em;
        }
        
        .customer_r {  
            width: 400px; 
            text-align: left; 
            line-height: 1.245em; 
            font-family: 'sarabun'; 
            font-size: 17px;
        }
        
        .info_invoice_number { 
            font: bold 16px 'sarabun'; 
            background-color: #ececec; 
            color: #000;
            line-height: 1.4em; 
            text-align: center; 
            height: 40px;  
            width: 500px; 
            left: 30px;
            top: 0px; 
        }
        
        .info_label_1 { 
            position: absolute; 
            top: 20; 
            left: 0; 
            padding: 0px; 
            margin: 0px; 
            border: 1px solid red;
        }
         
        ul, li { 
            margin: 0px; 
            padding: 0px;
        }
        
        ul { 
            margin-top: 10px; 
        }
        
        li { 
            margin-bottom: 10px;  
        }
        
        .body { 
            width: 100%; 
            height: 500px;
            top: 290px;   
        }
        
        .tablel td {
            padding: 0px; 
            font-size: 15px; 
            line-height: 0.9em;
        }
        
        .cordiabiupc, .cordiabiupc p { 
            font-family: 'sarabun';
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-left {
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="body">
        <div class="header pa">
            <div class="pr">
                <!-- Company Logo and Name -->
                <div class="pa" style="top:4px;left:30px;width:180px;">
                    <p style="font:bold 38px 'sarabun'; color:#dd232e;line-height:0.9em">RUBYSHOP</p>
                    @if(!empty($receipt_details->display_name))
                        <p style="font:bold 18px 'sarabun'; color:#000; line-height:.4em; letter-spacing:0.2px">{{ $receipt_details->display_name }}</p>
                    @endif
                </div>
                
                <!-- Company Details -->
                <div class="pa cordiabiupc" style="top:8px;left:210px;width:380px;line-height:0.5em;font-family: 'sarabun'; font-size:15px; font-weight:bold;letter-spacing: 0.2px;">
                    @if(!empty($receipt_details->display_name))
                        <p style="padding-bottom:4px;">{{ $receipt_details->display_name }}</p>
                    @endif
                    @if(!empty($receipt_details->address))
                        {!! $receipt_details->address !!}
                    @endif
                    @if(!empty($receipt_details->tax_info1))
                        <p>{{ $receipt_details->tax_label1 }}: {{ $receipt_details->tax_info1 }}</p>
                    @endif
                </div>

                <!-- Invoice Title -->
                <div class="pa" style="top:20px;left:350px; width:420px;font-weight:bold">
                    <div style="text-align:right;line-height:0.8em">
                        <span style="font:bold 26px 'sarabun'; color:#000;">ใบเสนอราคา</span><br>
                        <span style="font:bold 16px 'sarabun'; color:#000;">QUOTATION</span>
                        <br />
                        หน้าที่ 1/1
                    </div>
                </div>

                <!-- Company Footer Band -->
                <div class="pa" style="top:50px;left:-1px; width:100%;">
                    <div style="background-color:#dd242f; height:24px;color:#FFF;text-align:center; margin-top:3px;font-size:13px;line-height:1.8em;">
                        @if(!empty($receipt_details->contact))
                            {{ $receipt_details->contact }}
                        @endif
                    </div>
                    <div style="background-color:#676767; height:24px;color:#FFF;text-align:center;font-size:13px;line-height:1.8em;">
                        @if(!empty($receipt_details->tax_info1))
                            COMPANY ID: {{ $receipt_details->tax_info1 }}
                        @endif
                        @if(!empty($receipt_details->website))
                            {{ $receipt_details->website }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Number and Date -->
        <div class="pa" style="width:800px;height:32px;font:bold 17px 'sarabun'; color:#000000;background:#e9e9e9; top:110px;left:0px;padding-left:50px;line-height:1.1em">
            เลขที่เอกสาร : <span style="font:bold 18px 'sarabun'">{{ $receipt_details->invoice_no }}</span>  
            &nbsp;&nbsp;&nbsp; วันที่  :  <span style="font:bold 18px 'sarabun'">{{ $receipt_details->invoice_date }}</span>  
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; เงื่อนไขการชำระเงิน  :
        </div>

        <!-- Customer Address Label -->
        <div class="pa" style="top:200px; left:-63px;z-index:929">
            <div class="labelstyle" style="width:140px;height:38px;font:bold 18px 'sarabun'; color:#fff;line-height:1.1em;">
                <p align="center">ที่อยู่ลูกค้า</p>
            </div>
        </div>

        <!-- Customer Information Labels -->
        <div class="pa" style="top:150px; left:60px;z-index:999">
            <div class="customer_l" style="font:bold 15px 'sarabun';line-height:0.9em;">
                ชื่อบริษัท&nbsp;:<br />
                เลขประจำตัวผู้เสียภาษี&nbsp;:<br />
                ที่อยู่&nbsp;:<br /><br />
                ผู้ติดต่อ&nbsp;:<br />
                อีเมล์&nbsp;:
            </div>
        </div>

        <!-- Customer Phone -->
        <div class="pa" style="top:230px; left:200px;z-index:999">
            <div class="customer_l" style="font:bold 15px 'sarabun';line-height:0.9em;">
                เบอร์โทร&nbsp;:
            </div>
        </div>

        <!-- Customer Phone Value -->
        <div class="pa" style="top:230px; left:290px;width:350px; height:50px; line-height:.6em; font-family: 'sarabun'; font-size:16px;">
            <div class="customer_l" style="font:bold 15px 'sarabun';line-height:0.9em;">
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

        <!-- Customer Address Data -->
        <div class="pa" style="top:190px; left:180px;z-index:999;width:350px; height:50px; line-height:.6em; font-family: 'sarabun'; font-size:16px;">
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

        <!-- Customer Company and Details -->
        <div class="pa" style="top:150px; left:185px;z-index:999;">
            <div class="customer_r" style="font-size: 15px; font-style: normal;font-family: sarabun; line-height: 0.9em">
                @if(!empty($receipt_details->customer_info))
                    @php
                        $customer_info = strip_tags($receipt_details->customer_info);
                        $lines = explode("\n", $customer_info);
                        $company_name = !empty($lines[0]) ? trim($lines[0]) : '';
                    @endphp
                    {{ $company_name }}<br />
                @endif
                @if(!empty($receipt_details->customer_tax_number))
                    {{ $receipt_details->customer_tax_number }}<br />
                @endif
                &nbsp;<br /><br /><br />
            </div>
        </div>

        <!-- Sales Person Label -->
        <div class="pa" style="top:200px; left:427px;z-index:929">
            <div class="labelstyle" style="width:140px;height:38px;font:bold 18px 'sarabun'; color:#fff;line-height:1.1em;">
                <p align="center">ข้อมูลผู้เสนอราคา</p>
            </div>
        </div>

        <!-- Sales Person Labels -->
        <div class="pa" style="top:150px; left:535px;z-index:999">
            <div class="customer_l" style="width:60px;font:bold 14px 'sarabun';line-height:0.9em;">
                พนักงาน&nbsp;:<br />
                เบอร์โทร&nbsp;:<br />
                อีเมล์&nbsp;:<br />
                Line&nbsp;:
            </div>
        </div>

        <!-- Sales Person Data -->
        <div class="pa" style="top:150px; left:600px;z-index:999;">
            <div class="customer_r" style="font-size: 15px; font-style: normal;font-family: sarabun; line-height: 0.85em">
                @if(!empty($receipt_details->sales_person))
                    {{ $receipt_details->sales_person }}<br />
                @else
                    -<br />
                @endif
                -<br />
                -<br />
                -<br />
            </div>
        </div>

        <!-- Products Section -->
        <div class="body pa">
            <div class="pr">
                <!-- Products Label -->
                <div class="pa" style="top:240px; left:-275px;z-index:929">
                    <div class="labelstyle" style="width:520px;height:32px;font:bold 13px 'sarabun'; color:#fff;line-height:1.3em;">
                        <p align="center">PRODUCTS AND SERVICES DESCRIPTION</p>
                    </div>
                </div>
                <div class="pa" style="top:240px; left:-67px;z-index:999;">
                    <div class="labelstyle" style="font-family: 'sarabun';background:none;width:170px;height:32px;font:bold 13px 'sarabun'; color:#fff;line-height:1.3em;">
                        <p align="center">สินค้าและบริการ</p>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="pa" style="top:220px; left:40px;z-index:929;width:754px;">
                    <table style="width:100%" border="1" cellpadding="0" cellspacing="0">
                        <tr class="thead">
                            <td style="font-family:'sarabun'; font-size:15px; width:442px; padding:2px;">&nbsp;&nbsp;Description of Services and Goods</td>  
                            <td style="font-family:'sarabun'; font-size:15px;text-align:center; padding:2px;">Quantity</td>
                            <td style="font-family:'sarabun'; font-size:15px;text-align:center; line-height:12px; padding:2px;" align="center">Price&nbsp;Per Unit<br />(Baht)</td> 
                            <td style="font-family:'sarabun'; font-size:15px;text-align:center; padding:2px;">Amount</td>
                        </tr>

                        @forelse($receipt_details->lines as $index => $line)
                            <tr class="tablel">
                                <td style="text-align:left; font-size:11px; line-height:0.7em; padding:2px;">&nbsp;
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
                                <td style="text-align:center; font-size:11px; line-height:0.7em; padding:2px;" valign="top">
                                    {{ $line['quantity'] }} {{ $line['units'] }}
                                </td>
                                <td style="text-align:center; font-size:11px; line-height:0.7em; padding:2px;" valign="top">
                                    {{ $line['unit_price_before_discount'] }}
                                </td>
                                <td style="text-align:right; font-size:11px; line-height:0.7em; padding:2px;" valign="top">
                                    {{ $line['line_total'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center; padding:10px;">ไม่มีรายการสินค้า</td>
                            </tr>
                        @endforelse
                    </table>
                </div>

                <!-- Summary Section -->
                @php
                    $table_height = (count($receipt_details->lines) * 20) + 32;
                @endphp
                <div class="pa" style="top:{{ 220 + $table_height + 10 }}px; left:500px;z-index:999;width:254px;">
                    <table style="width:100%; font-size:11px;" border="1" cellpadding="2" cellspacing="0">
                        <tr>
                            <td style="text-align:right; padding:2px; background:#f5f5f5;">Subtotal:</td>
                            <td style="text-align:right; padding:2px;">{{ $receipt_details->subtotal }}</td>
                        </tr>
                        @if(!empty($receipt_details->discount))
                            <tr>
                                <td style="text-align:right; padding:2px; background:#f5f5f5;">Discount:</td>
                                <td style="text-align:right; padding:2px;">{{ $receipt_details->discount }}</td>
                            </tr>
                        @endif
                        @if(!empty($receipt_details->tax))
                            <tr>
                                <td style="text-align:right; padding:2px; background:#f5f5f5;">Tax:</td>
                                <td style="text-align:right; padding:2px;">{{ $receipt_details->tax }}</td>
                            </tr>
                        @endif
                        <tr style="background:#dc8285; color:#fff; font-weight:bold;">
                            <td style="text-align:right; padding:2px;">Total:</td>
                            <td style="text-align:right; padding:2px;">{{ $receipt_details->total }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Signature Section -->
                @php
                    $footer_top = 220 + $table_height + 120;
                @endphp
                <div class="pa" style="left:0px;z-index:929;width:1000px;top:{{ $footer_top }}px;height:225px">
                    <div class="pr">
                        <div style="clear:both"></div>
                        <div class="pa" style="top:10px">
                            <!-- Received By -->
                            <div style="display:inline-block; width:160px;">
                                <div style="background:#ececec;text-align:center; height:40px;">
                                    <div style="line-height:.7em; margin-top:5px;">ผู้รับสินค้า<br />Received By</div>
                                </div>
                                <div style="font: bold 13px 'sarabun'; padding-left:10px; margin-top:10px; line-height:.7em">
                                    ได้รับสินค้าครบตามรายการพร้อม<br />
                                    ได้รับใบกำกับภาษีเรียบร้อยแล้ว<br />
                                    โปรดลงลายมือชื่อด้วยตัวบรรจง
                                </div>
                                <br />
                                <div style="position:relative;">
                                    <div style="display:inline-block; line-height:2.4em ; font-size:14px; position:absolute; text-align:center; left:18px; top:1px;">
                                        ผู้รับสินค้า/Received By<br />
                                        วันที่/Date
                                    </div>
                                    <div style="text-align:center;position:absolute; top:-5px; left:10px; line-height:1.9em">
                                        .................................<br />
                                        .................................
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="pa" style="top:0;left:150px">
                            <div style="display:inline-block; width:180px; margin-left:-4px;">
                                <div style="background:#ececec;text-align:center; height:40px;">
                                    <div style="line-height:.7em; margin-top:5px;">เงื่อนไขข้อมตกลง<br />Terms and Conditions</div>
                                </div>
                                <div style="font: bold 14px 'sarabun'; padding-left:10px; line-height:.7em">
                                    <ul>
                                        <li>ได้รับสินค้าตามรานการข้างต้นนี้ถูกต้อง<br />และยินยอมในเงื่อนไขตามเอกสารนี้</li>
                                        <li>กรุณาสั่งจ่ายเช็คขีดคร่อมในนาม<br />{{ $receipt_details->display_name ?: 'ห้างหุ้นส่วนจำกัดรูบี้ช๊อป' }}</li>
                                        <li>บริษัทจะคิดอัตราดอกเบี้ย 1.5% ต่อเดือนสำหรับใบแจ้งหนี้ที่ไม่ชำระ<br />ตามกำหนด</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery By -->
                        <div class="pa" style="width:190px; left:300px">
                            <div style="background:#ececec; text-align:center;height:40px;">
                                <div style="line-height:.7em; margin-top:5px;">ผู้ส่งสินค้า<br />Delivery By</div>
                            </div>
                            <div style="position:relative; margin-top:40px;">
                                <div style="display:inline-block; line-height:2.4em ; font-size:14px; position:absolute; text-align:center; left:18px; top:3px;">
                                    ผู้ตรวจสอบสินค้า/QC1<br />
                                    ผู้ส่งสินค้า/Delivered By
                                </div>
                                <div style="text-align:center;position:absolute; top:-5px; left:10px; line-height:1.9em">
                                    ...........................................<br />
                                    ...........................................
                                </div>
                            </div>
                        </div>

                        <!-- Approve By -->
                        <div class="pa" style="width:289px; left:490px">
                            <div style="background:#F00; color:#fff; text-align:center;height:40px;">
                                <div style="margin-top:5px; font: bold 18px 'sarabun';">Approve By/ผู้มีอํานาจอนุมัติ</div>
                            </div>
                            <div style="text-align:center; margin-top:69px;">
                                .....................................................................
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
