<x-PDF-Layout>

<body>
<style type="text/css" media="all">
    body {
      font-family:verdana;
      margin:0;
      padding:0;
    }
    @page{
      margin:0;
      padding:0;
      
    }
    .label{
      width:99.1mm !important;
      height:57.3mm !important;
      overflow:hidden;
      border-radius:2mm;
    }

    .namediv{
      position:relative;
      width:280px;
      left:10px;
      font-size:14pt;
      height:25px;
      z-index: 1;
    }
    .namediv span{
      width:249.9px;
    }
    .pricediv{
      position:relative;
      float:right;
      right:20px;
      top:-20px;
      z-index: 1;
      text-align:right;
      width:50px;
      height:20px;
    }
    .pricediv span{
      width:49px;
    }
    .descdiv{
      width:374px;
      margin-top:10px;
      height:80px !important;
      font-size:2px;
      position:relative;
      text-align: justify;
    }
    .descdiv span{
      width:373px;
    }

    .wrapper{
      page-break-after: always;
      height:286.5mm;
      margin: 5.25mm 2.65mm 5.25mm 2.65mm;
      display:block;
    }
    svg{
      position:relative;
      height:80px;
      width:80px;

    }

    .logo{
      height:78px;
      width:288px;
      position:relative;
    }
    .stick{
      position:relative;
      display:inline;
      height:75px;
      z-index: 2;
    }
    table{
      border-collapse:collapse;
      margin:0;


    }
    tr{
      height: 57.3mm !important;
      width:200mm !important;
    }
    td{
      padding:2px;
    }
    </style>

<!--Measure the labels and find the margins. -->
<div class="wrapper">
<table>
    <tr>
  @foreach($request->items as $q)
    @foreach(range(1, $q->quantity) as $items)
      @foreach($q->product as $label)
        @if($divid % 2 == 1 && $divid !== 1)
          </tr>
          <tr>
        @endif
        @if($divid % 2 == 0)
          <td style="width:4mm !important;"></td>
        @endif
            <td class="label" id="{{$divid}}">
            <div class="namediv namediv{{$divid}}"><span class="name">{{$label->name}}</span></div><div class="pricediv pricediv{{$divid}}"><span class="price">£{{$label->price}}</span></div>
            <div class="descdiv descriptiondiv{{$divid}}"><span>{{$label->description}}
              @if(strpos($label->description, 'hardy') OR strpos($label->description, 'Hardy'))
                
              @else
                @if($label->hardiness_zone === 'Red')
                Protect from frosts (4°C and above).
                @elseif($label->hardiness_zone === 'Amber')
                Hardy to -2°C.
                @elseif($label->hardiness_zone === 'Green')
                UK hardy.
                @endif
              @endif 
            </span></div>

            <div class="stick">
            <img class="logo" src="{{$image}}" alt="">
            <!--{!! QrCode::generate(URL::to('/product/') . $label->slug); !!}-->
            {!! QrCode::generate($label->slug); !!}

            </div>
            </td>
            @if($divid % 10 == 0 && $divid !== 1)
            </table>
            </div>
            <div class="wrapper">
            <table>


            @endif
            <script>
            $(document).ready(function () {
              $('.descriptiondiv{{$divid}}').textfill({
                changeLineHeight: true,
                minFontPixels: 6,
              })
              $('.namediv{{$divid}}').textfill({
                changeLineHeight: true,
                minFontPixels: 6,
              })
              $('.pricediv{{$divid}}').textfill({
                changeLineHeight: true,
                minFontPixels: 6,
              })
            })
            </script>
            <span hidden>{{$divid++}}</span>
          </div>

      @endforeach
    @endforeach
  @endforeach
  </tr>
</table>
</body>

</x-PDF-Layout>