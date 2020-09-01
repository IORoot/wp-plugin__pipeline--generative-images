<?php

namespace genimage\svg;

class build_shape
{
    public $shape;

    public $args;

    public $shape_type;

    public $cell_size = 80;

    public function __construct($args)
    {
        $this->args = $args;

        $this->shape_type = $this->args['shape_type'];
    }


    public function render()
    {
        $switch = $this->shape_type;
        $this->$switch();

        return $this->shape;
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │                               Basic Shapes                              │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘

    public function rect()
    {
        $shape = '<rect';
        foreach ($this->args as $key => $value) {
            $shape .= ' '.$key.'="'.$value.'" ';
        }
        $shape .= ' ></rect>';

        $this->shape = $shape;

        return;
    }

    
    public function circle()
    {
        $r = $this->args['width']/2;
        $cx = $this->args['x'] - $r+($r*2);
        $cy = $this->args['y'] - $r+($r*2);

        $shape = '<circle';
        $shape .= ' cx="' . $cx. '" ';
        $shape .= ' cy="' . $cy. '" ';
        $shape .= ' r="' . $r*$this->args['scale']. '" ';
        $shape .= ' fill="' . $this->args['fill']. '" ';
        $shape .= ' opacity="' . $this->args['opacity']. '" ';
        $shape .= ' filter="' . $this->args['filter']. '" ';
        $shape .= ' ></circle>';

        $this->shape = $shape;

        return;
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │                              Polygon types                              │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘

    public function triangle()
    {
        $points = '80 80 40 10 0 80';
        $this->shape = $this->polygon($points);

        return;
    }



    public function cross()
    {
        $points = '28.75 -5 28.75 28.75 -5 28.75 -5 51.25 28.75 51.25 28.75 85 51.25 85 51.25 51.25 85 51.25 85 28.75 51.25 28.75 51.25 -5';
        $poly_params = ' transform="translate(40.000000, 40.000000) rotate(-315.000000) translate(-40.000000, -40.000000)" ';
        $this->shape = $this->polygon($points, null, $poly_params);

        return;
    }



    public function square_plus()
    {
        $points = '30 0 30 30 0 30 0 50 30 50 30 80 50 80 50 50 80 50 80 30 50 30 50 0';
        $this->shape = $this->polygon($points);

        return;
    }



    public function right_angled_triangle()
    {
        $triangle = [
            'tl' => '0 0 ',
            'tr' => '80 0 ',
            'bl' => '80 80 ',
            'br' => '0 80 '
        ];

        shuffle($triangle);
        $points = $triangle[0] . $triangle[1]. $triangle[2];
        $this->shape = $this->polygon($points);

        return;
    }


    public function polygon($points, $svg_params = null, $poly_params = null)
    {
        $shape = '<svg viewBox="0 0 80 80" ';
        $shape .= ' x="' . $this->args['x']. '" ';
        $shape .= ' y="' . $this->args['y']. '" ';
        $shape .= ' width="' . $this->args['width']*$this->args['scale']. '" ';
        $shape .= ' height="' . $this->args['height']*$this->args['scale']. '" ';
        $shape .= ' filter="' . $this->args['filter']. '" ';
        $shape .= $svg_params;
        $shape .= '>';

        $shape .= '<polygon ';
        $shape .= ' points="' .  $points . '" ';
        $shape .= ' fill="' . $this->args['fill']. '" ';
        $shape .= ' fill-opacity="' . $this->args['opacity']. '" ';
        $shape .= $poly_params;
        $shape .= '></polygon>';

        $shape .= '</svg>';

        return $shape;
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │                                Path types                               │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘


    public function square_cross()
    {        
        $points = 'M32 16 h16 v16 h16 v16 h-16 v16 h-16 v-16 h-16 v-16 h16 z';
        $this->shape = $this->path($points);
        return;
    }


    public function leaf()
    {        
        $points = 'M9.2e-14,-7.1e-15 C9.2e-14,53.3 26.6,80 80,80 C80,26.6 53,-7.1e-15 9e-14,-7 Z';
        $path_params = 'transform="rotate('.$this->args['rotate'].' 40 40)"';
        $this->shape = $this->path($points, null, $path_params);
        return;
    }


    public function lines()
    {        
        $points = 'M80,64 L80,72 L0,72 L0,64 L80,64 Z M80,48 L80,56 L0,56 L0,48 L80,48 Z M80,32 L80,40 L0,40 L0,32 L80,32 Z M80,16 L80,24 L0,24 L0,16 L80,16 Z M80,0 L80,8 L0,8 L0,0 L80,0 Z';
        $this->args['scale'] = 1;
        $this->shape = $this->path($points, null, null);
        return;
    }

    public function diamond()
    {        
        $points = 'M40,0 L80,40 L40,80 L0,40 L40,0 Z M40,22 L22,40 L40,57 L57,40 L40,22 Z';
        $this->shape = $this->path($points, null, null);
        return;
    }

    public function flower()
    {
        $points = 'M40,40 C80,40 80,80 80,80 C80,80 40,80 40,40 Z M40,40 C40,40 40,80 0,80 C0,42 36.1,40.1 39.71,40.005 L39.71,40.005 Z M0,0 C40,0 40,40 40,40 C40,40 0,40 0,0 Z M80,0 C80,0 80,40 40,40 C40,2.4 75.344,0.144 79.58528,0.00864 L79.58528,0.00864 Z';
        $this->shape = $this->path($points, null, null);
        return;
    }

    public function stripes()
    {        
        $points = 'M80,0 L0,80 L0,70 L70,0 L80,0 Z M80,10 L80,20 L20,80 L10,80 L80,10 Z M80,30 L80,40 L40,80 L30,80 L80,30 Z M80,50 L80,60 L60,80 L50,80 L80,50 Z M80,70 L80,80 L70,80 L80,70 Z M60,0 L0,60 L0,50 L50,0 L60,0 Z M40,0 L0,40 L0,30 L30,0 L40,0 Z M20,0 L0,20 L0,10 L10,0 L20,0 Z';
        $this->shape = $this->path($points, null, null);
        return;
    }

    public function bump()
    {        
        $points = 'M0,80 L80,80 L80,80 L80,0 L80,0 C33,0 0,33 0,80 Z';
        $this->shape = $this->path($points, null, null);
        return;
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │                            Complex Path Types                           │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘


    public function dots()
    {        
        $points = 'M10,68 C11.1045695,68 12,68.8954305 12,70 C12,71.1045695 11.1045695,72 10,72 C8.8954305,72 8,71.1045695 8,70 C8,68.8954305 8.8954305,68 10,68 Z M20,68 C21.1045695,68 22,68.8954305 22,70 C22,71.1045695 21.1045695,72 20,72 C18.8954305,72 18,71.1045695 18,70 C18,68.8954305 18.8954305,68 20,68 Z M30,68 C31.1045695,68 32,68.8954305 32,70 C32,71.1045695 31.1045695,72 30,72 C28.8954305,72 28,71.1045695 28,70 C28,68.8954305 28.8954305,68 30,68 Z M40,68 C41.1045695,68 42,68.8954305 42,70 C42,71.1045695 41.1045695,72 40,72 C38.8954305,72 38,71.1045695 38,70 C38,68.8954305 38.8954305,68 40,68 Z M50,68 C51.1045695,68 52,68.8954305 52,70 C52,71.1045695 51.1045695,72 50,72 C48.8954305,72 48,71.1045695 48,70 C48,68.8954305 48.8954305,68 50,68 Z M60,68 C61.1045695,68 62,68.8954305 62,70 C62,71.1045695 61.1045695,72 60,72 C58.8954305,72 58,71.1045695 58,70 C58,68.8954305 58.8954305,68 60,68 Z M70,68 C71.1045695,68 72,68.8954305 72,70 C72,71.1045695 71.1045695,72 70,72 C68.8954305,72 68,71.1045695 68,70 C68,68.8954305 68.8954305,68 70,68 Z M10,58 C11.1045695,58 12,58.8954305 12,60 C12,61.1045695 11.1045695,62 10,62 C8.8954305,62 8,61.1045695 8,60 C8,58.8954305 8.8954305,58 10,58 Z M20,58 C21.1045695,58 22,58.8954305 22,60 C22,61.1045695 21.1045695,62 20,62 C18.8954305,62 18,61.1045695 18,60 C18,58.8954305 18.8954305,58 20,58 Z M30,58 C31.1045695,58 32,58.8954305 32,60 C32,61.1045695 31.1045695,62 30,62 C28.8954305,62 28,61.1045695 28,60 C28,58.8954305 28.8954305,58 30,58 Z M40,58 C41.1045695,58 42,58.8954305 42,60 C42,61.1045695 41.1045695,62 40,62 C38.8954305,62 38,61.1045695 38,60 C38,58.8954305 38.8954305,58 40,58 Z M50,58 C51.1045695,58 52,58.8954305 52,60 C52,61.1045695 51.1045695,62 50,62 C48.8954305,62 48,61.1045695 48,60 C48,58.8954305 48.8954305,58 50,58 Z M60,58 C61.1045695,58 62,58.8954305 62,60 C62,61.1045695 61.1045695,62 60,62 C58.8954305,62 58,61.1045695 58,60 C58,58.8954305 58.8954305,58 60,58 Z M70,58 C71.1045695,58 72,58.8954305 72,60 C72,61.1045695 71.1045695,62 70,62 C68.8954305,62 68,61.1045695 68,60 C68,58.8954305 68.8954305,58 70,58 Z M10,48 C11.1045695,48 12,48.8954305 12,50 C12,51.1045695 11.1045695,52 10,52 C8.8954305,52 8,51.1045695 8,50 C8,48.8954305 8.8954305,48 10,48 Z M20,48 C21.1045695,48 22,48.8954305 22,50 C22,51.1045695 21.1045695,52 20,52 C18.8954305,52 18,51.1045695 18,50 C18,48.8954305 18.8954305,48 20,48 Z M30,48 C31.1045695,48 32,48.8954305 32,50 C32,51.1045695 31.1045695,52 30,52 C28.8954305,52 28,51.1045695 28,50 C28,48.8954305 28.8954305,48 30,48 Z M40,48 C41.1045695,48 42,48.8954305 42,50 C42,51.1045695 41.1045695,52 40,52 C38.8954305,52 38,51.1045695 38,50 C38,48.8954305 38.8954305,48 40,48 Z M50,48 C51.1045695,48 52,48.8954305 52,50 C52,51.1045695 51.1045695,52 50,52 C48.8954305,52 48,51.1045695 48,50 C48,48.8954305 48.8954305,48 50,48 Z M60,48 C61.1045695,48 62,48.8954305 62,50 C62,51.1045695 61.1045695,52 60,52 C58.8954305,52 58,51.1045695 58,50 C58,48.8954305 58.8954305,48 60,48 Z M70,48 C71.1045695,48 72,48.8954305 72,50 C72,51.1045695 71.1045695,52 70,52 C68.8954305,52 68,51.1045695 68,50 C68,48.8954305 68.8954305,48 70,48 Z M40,38 C41.1045695,38 42,38.8954305 42,40 C42,41.1045695 41.1045695,42 40,42 C38.8954305,42 38,41.1045695 38,40 C38,38.8954305 38.8954305,38 40,38 Z M60,38 C61.1045695,38 62,38.8954305 62,40 C62,41.1045695 61.1045695,42 60,42 C58.8954305,42 58,41.1045695 58,40 C58,38.8954305 58.8954305,38 60,38 Z M10,38 C11.1045695,38 12,38.8954305 12,40 C12,41.1045695 11.1045695,42 10,42 C8.8954305,42 8,41.1045695 8,40 C8,38.8954305 8.8954305,38 10,38 Z M30,38 C31.1045695,38 32,38.8954305 32,40 C32,41.1045695 31.1045695,42 30,42 C28.8954305,42 28,41.1045695 28,40 C28,38.8954305 28.8954305,38 30,38 Z M70,38 C71.1045695,38 72,38.8954305 72,40 C72,41.1045695 71.1045695,42 70,42 C68.8954305,42 68,41.1045695 68,40 C68,38.8954305 68.8954305,38 70,38 Z M50,38 C51.1045695,38 52,38.8954305 52,40 C52,41.1045695 51.1045695,42 50,42 C48.8954305,42 48,41.1045695 48,40 C48,38.8954305 48.8954305,38 50,38 Z M20,38 C21.1045695,38 22,38.8954305 22,40 C22,41.1045695 21.1045695,42 20,42 C18.8954305,42 18,41.1045695 18,40 C18,38.8954305 18.8954305,38 20,38 Z M70,28 C71.1045695,28 72,28.8954305 72,30 C72,31.1045695 71.1045695,32 70,32 C68.8954305,32 68,31.1045695 68,30 C68,28.8954305 68.8954305,28 70,28 Z M10,28 C11.1045695,28 12,28.8954305 12,30 C12,31.1045695 11.1045695,32 10,32 C8.8954305,32 8,31.1045695 8,30 C8,28.8954305 8.8954305,28 10,28 Z M30,28 C31.1045695,28 32,28.8954305 32,30 C32,31.1045695 31.1045695,32 30,32 C28.8954305,32 28,31.1045695 28,30 C28,28.8954305 28.8954305,28 30,28 Z M50,28 C51.1045695,28 52,28.8954305 52,30 C52,31.1045695 51.1045695,32 50,32 C48.8954305,32 48,31.1045695 48,30 C48,28.8954305 48.8954305,28 50,28 Z M20,28 C21.1045695,28 22,28.8954305 22,30 C22,31.1045695 21.1045695,32 20,32 C18.8954305,32 18,31.1045695 18,30 C18,28.8954305 18.8954305,28 20,28 Z M60,28 C61.1045695,28 62,28.8954305 62,30 C62,31.1045695 61.1045695,32 60,32 C58.8954305,32 58,31.1045695 58,30 C58,28.8954305 58.8954305,28 60,28 Z M40,28 C41.1045695,28 42,28.8954305 42,30 C42,31.1045695 41.1045695,32 40,32 C38.8954305,32 38,31.1045695 38,30 C38,28.8954305 38.8954305,28 40,28 Z M10,18 C11.1045695,18 12,18.8954305 12,20 C12,21.1045695 11.1045695,22 10,22 C8.8954305,22 8,21.1045695 8,20 C8,18.8954305 8.8954305,18 10,18 Z M20,18 C21.1045695,18 22,18.8954305 22,20 C22,21.1045695 21.1045695,22 20,22 C18.8954305,22 18,21.1045695 18,20 C18,18.8954305 18.8954305,18 20,18 Z M30,18 C31.1045695,18 32,18.8954305 32,20 C32,21.1045695 31.1045695,22 30,22 C28.8954305,22 28,21.1045695 28,20 C28,18.8954305 28.8954305,18 30,18 Z M40,18 C41.1045695,18 42,18.8954305 42,20 C42,21.1045695 41.1045695,22 40,22 C38.8954305,22 38,21.1045695 38,20 C38,18.8954305 38.8954305,18 40,18 Z M50,18 C51.1045695,18 52,18.8954305 52,20 C52,21.1045695 51.1045695,22 50,22 C48.8954305,22 48,21.1045695 48,20 C48,18.8954305 48.8954305,18 50,18 Z M60,18 C61.1045695,18 62,18.8954305 62,20 C62,21.1045695 61.1045695,22 60,22 C58.8954305,22 58,21.1045695 58,20 C58,18.8954305 58.8954305,18 60,18 Z M70,18 C71.1045695,18 72,18.8954305 72,20 C72,21.1045695 71.1045695,22 70,22 C68.8954305,22 68,21.1045695 68,20 C68,18.8954305 68.8954305,18 70,18 Z M10,8 C11.1045695,8 12,8.8954305 12,10 C12,11.1045695 11.1045695,12 10,12 C8.8954305,12 8,11.1045695 8,10 C8,8.8954305 8.8954305,8 10,8 Z M20,8 C21.1045695,8 22,8.8954305 22,10 C22,11.1045695 21.1045695,12 20,12 C18.8954305,12 18,11.1045695 18,10 C18,8.8954305 18.8954305,8 20,8 Z M30,8 C31.1045695,8 32,8.8954305 32,10 C32,11.1045695 31.1045695,12 30,12 C28.8954305,12 28,11.1045695 28,10 C28,8.8954305 28.8954305,8 30,8 Z M40,8 C41.1045695,8 42,8.8954305 42,10 C42,11.1045695 41.1045695,12 40,12 C38.8954305,12 38,11.1045695 38,10 C38,8.8954305 38.8954305,8 40,8 Z M50,8 C51.1045695,8 52,8.8954305 52,10 C52,11.1045695 51.1045695,12 50,12 C48.8954305,12 48,11.1045695 48,10 C48,8.8954305 48.8954305,8 50,8 Z M60,8 C61.1045695,8 62,8.8954305 62,10 C62,11.1045695 61.1045695,12 60,12 C58.8954305,12 58,11.1045695 58,10 C58,8.8954305 58.8954305,8 60,8 Z M70,8 C71.1045695,8 72,8.8954305 72,10 C72,11.1045695 71.1045695,12 70,12 C68.8954305,12 68,11.1045695 68,10 C68,8.8954305 68.8954305,8 70,8 Z';
        $this->shape = $this->path($points, null, null);
        return;
    }

    public function wiggles()
    {        
        $points = 'M79.5479146,16.7335244 C80.2473697,17.588414 80.1213652,18.8484594 79.2664756,19.5479146 C76.7719813,21.5888645 74.9214507,22.1900004 71.546712,22.53423 L70.455436,22.6422309 C69.6878308,22.7253305 69.2532303,22.8020445 68.7782134,22.9381775 C67.6228444,23.2692895 66.6403114,23.9313898 65.547884,25.266513 C64.1009929,27.0348482 63.7118989,28.2147405 63.4428065,30.8804742 L63.3531309,31.7998052 C63.2395618,32.9010191 63.1324356,33.5375761 62.9066693,34.3250272 C62.3468374,36.2776673 61.2199826,37.9495907 59.2664756,39.5479146 C56.7719697,41.588874 54.9216349,42.1900613 51.5474295,42.5343762 L50.4589879,42.6421057 C49.6943266,42.72483 49.2611777,42.8010074 48.7877102,42.9359497 C47.6280032,43.2664761 46.6432147,43.9286194 45.5475231,45.2669539 C44.0996659,47.0354417 43.7104784,48.2150808 43.4416007,50.8804102 L43.3523278,51.7965212 C43.239545,52.8918351 43.1334935,53.5249542 42.9104705,54.3080667 C42.3524456,56.2674887 41.2254136,57.9441206 39.2669613,59.547517 C36.7721012,61.5900738 34.9212043,62.1915651 31.5462525,62.5355303 L30.6745976,62.6203982 C29.7710092,62.7124465 29.3023977,62.789706 28.7857865,62.9369794 C27.6268523,63.2673634 26.6425906,63.9292522 25.5475833,65.2668804 C24.0183441,67.1349533 23.5790637,68.336176 23.1969911,71.0122195 L23.0511859,72.0604441 C23.0231021,72.252272 22.9974311,72.4152912 22.9673936,72.593475 C22.518502,75.2563197 21.5726268,77.2558004 19.4142136,79.4142136 C18.633165,80.1952621 17.366835,80.1952621 16.5857864,79.4142136 C15.8047379,78.633165 15.8047379,77.366835 16.5857864,76.5857864 C18.1192217,75.0523511 18.705298,73.8134477 19.0230459,71.9285529 L19.1150873,71.3299731 L19.1855531,70.8183896 C19.670649,67.2320889 20.2969423,65.3661826 22.4524167,62.7331196 C24.054974,60.7754835 25.7308433,59.6485078 27.6891729,59.0902355 C28.4729669,58.8667948 29.1066185,58.7605879 30.2028578,58.6477043 L30.792363,58.5901325 C33.6914649,58.3189659 34.8938313,57.9582529 36.7330387,56.452483 C38.0712573,55.3568754 38.7331571,54.3721987 39.0634374,53.2124681 L39.131526,52.9560799 C39.2366094,52.5279825 39.3029721,52.077347 39.3796227,51.3248907 L39.4272397,50.8349642 C39.7658683,47.2149726 40.3342934,45.3203051 42.4524769,42.7330461 C44.0557727,40.7746975 45.7322212,39.6475021 47.6913358,39.0891376 C48.4747453,38.8658592 49.1081429,38.7596588 50.2038687,38.6466909 L50.7930939,38.5890696 C53.6919536,38.3175365 54.8943381,37.9568742 56.7335244,36.4520854 C58.0683609,35.3599465 58.7304381,34.3776173 59.0615815,33.2226208 L59.1303832,32.9652196 C59.2471576,32.4924348 59.3164316,31.9921563 59.4040458,31.0908339 L59.4284448,30.8347949 C59.7673333,27.2147932 60.3354485,25.3203975 62.452116,22.733487 C64.0508282,20.7796018 65.7230989,19.6527075 67.6762321,19.0929678 C68.4633829,18.8673818 69.0997085,18.7603187 70.2005153,18.6467842 L70.7924741,18.5888989 C73.6918545,18.3174335 74.8944495,17.9567831 76.7335244,16.4520854 C77.588414,15.7526303 78.8484594,15.8786348 79.5479146,16.7335244 Z M71.5479146,8.73352442 C72.2473697,9.58841405 72.1213652,10.8484594 71.2664756,11.5479146 C68.7719813,13.5888645 66.9214507,14.1900004 63.546712,14.53423 L62.455436,14.6422309 C61.6878308,14.7253305 61.2532303,14.8020445 60.7782134,14.9381775 C59.6228444,15.2692895 58.6403114,15.9313898 57.547884,17.266513 C56.1009929,19.0348482 55.7118989,20.2147405 55.4428065,22.8804742 L55.3531309,23.7998052 C55.2395618,24.9010191 55.1324356,25.5375761 54.9066693,26.3250272 C54.3468374,28.2776673 53.2199826,29.9495907 51.2664756,31.5479146 C48.7719697,33.588874 46.9216349,34.1900613 43.5474295,34.5343762 L42.4589879,34.6421057 C41.6943266,34.72483 41.2611777,34.8010074 40.7877102,34.9359497 C39.6280032,35.2664761 38.6432147,35.9286194 37.5475231,37.2669539 C36.0996659,39.0354417 35.7104784,40.2150808 35.4416007,42.8804102 L35.3523278,43.7965212 C35.239545,44.8918351 35.1334935,45.5249542 34.9104705,46.3080667 C34.3524456,48.2674887 33.2254136,49.9441206 31.2669613,51.547517 C28.7721012,53.5900738 26.9212043,54.1915651 23.5462525,54.5355303 L22.6745976,54.6203982 C21.7710092,54.7124465 21.3023977,54.789706 20.7857865,54.9369794 C19.6268523,55.2673634 18.6425906,55.9292522 17.5475833,57.2668804 C16.0183441,59.1349533 15.5790637,60.336176 15.1969911,63.0122195 L15.0511859,64.0604441 C15.0231021,64.252272 14.9974311,64.4152912 14.9673936,64.593475 C14.518502,67.2563197 13.5726268,69.2558004 11.4142136,71.4142136 C10.633165,72.1952621 9.36683502,72.1952621 8.58578644,71.4142136 C7.80473785,70.633165 7.80473785,69.366835 8.58578644,68.5857864 C10.1192217,67.0523511 10.705298,65.8134477 11.0230459,63.9285529 L11.1150873,63.3299731 L11.1855531,62.8183896 C11.670649,59.2320889 12.2969423,57.3661826 14.4524167,54.7331196 C16.054974,52.7754835 17.7308433,51.6485078 19.6891729,51.0902355 C20.4729669,50.8667948 21.1066185,50.7605879 22.2028578,50.6477043 L22.792363,50.5901325 C25.6914649,50.3189659 26.8938313,49.9582529 28.7330387,48.452483 C30.0712573,47.3568754 30.7331571,46.3721987 31.0634374,45.2124681 L31.131526,44.9560799 C31.2366094,44.5279825 31.3029721,44.077347 31.3796227,43.3248907 L31.4272397,42.8349642 C31.7658683,39.2149726 32.3342934,37.3203051 34.4524769,34.7330461 C36.0557727,32.7746975 37.7322212,31.6475021 39.6913358,31.0891376 C40.4747453,30.8658592 41.1081429,30.7596588 42.2038687,30.6466909 L42.7930939,30.5890696 C45.6919536,30.3175365 46.8943381,29.9568742 48.7335244,28.4520854 C50.0683609,27.3599465 50.7304381,26.3776173 51.0615815,25.2226208 L51.1303832,24.9652196 C51.2471576,24.4924348 51.3164316,23.9921563 51.4040458,23.0908339 L51.4284448,22.8347949 C51.7673333,19.2147932 52.3354485,17.3203975 54.452116,14.733487 C56.0508282,12.7796018 57.7230989,11.6527075 59.6762321,11.0929678 C60.4633829,10.8673818 61.0997085,10.7603187 62.2005153,10.6467842 L62.7924741,10.5888989 C65.6918545,10.3174335 66.8944495,9.95678307 68.7335244,8.4520854 C69.588414,7.75263025 70.8484594,7.87863479 71.5479146,8.73352442 Z M64.5479146,0.733524419 C65.2473697,1.58841405 65.1213652,2.84845945 64.2664756,3.5479146 C61.7719813,5.58886447 59.9214507,6.19000036 56.546712,6.53423004 L55.455436,6.64223094 C54.6878308,6.72533049 54.2532303,6.80204451 53.7782134,6.93817745 C52.6228444,7.26928947 51.6403114,7.93138975 50.547884,9.26651299 C49.1009929,11.0348482 48.7118989,12.2147405 48.4428065,14.8804742 L48.3531309,15.7998052 C48.2395618,16.9010191 48.1324356,17.5375761 47.9066693,18.3250272 C47.3468374,20.2776673 46.2199826,21.9495907 44.2664756,23.5479146 C41.7719697,25.588874 39.9216349,26.1900613 36.5474295,26.5343762 L35.4589879,26.6421057 C34.6943266,26.72483 34.2611777,26.8010074 33.7877102,26.9359497 C32.6280032,27.2664761 31.6432147,27.9286194 30.5475231,29.2669539 C29.0996659,31.0354417 28.7104784,32.2150808 28.4416007,34.8804102 L28.3523278,35.7965212 C28.239545,36.8918351 28.1334935,37.5249542 27.9104705,38.3080667 C27.3524456,40.2674887 26.2254136,41.9441206 24.2669613,43.547517 C21.7721012,45.5900738 19.9212043,46.1915651 16.5462525,46.5355303 L15.6745976,46.6203982 C14.7710092,46.7124465 14.3023977,46.789706 13.7857865,46.9369794 C12.6268523,47.2673634 11.6425906,47.9292522 10.5475833,49.2668804 C9.01834409,51.1349533 8.57906373,52.336176 8.19699107,55.0122195 L8.05118591,56.0604441 C8.02310208,56.252272 7.99743108,56.4152912 7.96739359,56.593475 C7.51850204,59.2563197 6.57262675,61.2558004 4.41421356,63.4142136 C3.63316498,64.1952621 2.36683502,64.1952621 1.58578644,63.4142136 C0.804737854,62.633165 0.804737854,61.366835 1.58578644,60.5857864 C3.11922174,59.0523511 3.705298,57.8134477 4.02304591,55.9285529 L4.11508733,55.3299731 L4.18555313,54.8183896 C4.67064904,51.2320889 5.29694234,49.3661826 7.45241674,46.7331196 C9.054974,44.7754835 10.7308433,43.6485078 12.6891729,43.0902355 C13.4729669,42.8667948 14.1066185,42.7605879 15.2028578,42.6477043 L15.792363,42.5901325 C18.6914649,42.3189659 19.8938313,41.9582529 21.7330387,40.452483 C23.0712573,39.3568754 23.7331571,38.3721987 24.0634374,37.2124681 L24.131526,36.9560799 C24.2366094,36.5279825 24.3029721,36.077347 24.3796227,35.3248907 L24.4272397,34.8349642 C24.7658683,31.2149726 25.3342934,29.3203051 27.4524769,26.7330461 C29.0557727,24.7746975 30.7322212,23.6475021 32.6913358,23.0891376 C33.4747453,22.8658592 34.1081429,22.7596588 35.2038687,22.6466909 L35.7930939,22.5890696 C38.6919536,22.3175365 39.8943381,21.9568742 41.7335244,20.4520854 C43.0683609,19.3599465 43.7304381,18.3776173 44.0615815,17.2226208 L44.1303832,16.9652196 C44.2471576,16.4924348 44.3164316,15.9921563 44.4040458,15.0908339 L44.4284448,14.8347949 C44.7673333,11.2147932 45.3354485,9.32039749 47.452116,6.73348701 C49.0508282,4.77960183 50.7230989,3.65270751 52.6762321,3.09296782 C53.4633829,2.86738182 54.0997085,2.76031871 55.2005153,2.64678416 L55.7924741,2.58889892 C58.6918545,2.31743346 59.8944495,1.95678307 61.7335244,0.452085402 C62.588414,-0.247369748 63.8484594,-0.121365207 64.5479146,0.733524419 Z';
        $this->shape = $this->path($points, null, null);
        return;
    }




    public function path($points, $svg_params = null, $path_params = null)
    {
        $shape = '<svg viewBox="0 0 80 80" ';
        $shape .= ' x="' . $this->args['x']. '" ';
        $shape .= ' y="' . $this->args['y']. '" ';
        $shape .= ' width="' . $this->args['width']*$this->args['scale']. '" ';
        $shape .= ' height="' . $this->args['height']*$this->args['scale']. '" ';
        $shape .= ' filter="' . $this->args['filter']. '" ';
        $shape .= $svg_params;
        $shape .= '>';

        $shape .= '<path ';
        $shape .= ' d="' . $points . '" ';
        $shape .= ' fill="' . $this->args['fill']. '" ';
        $shape .= ' fill-opacity="' . $this->args['opacity']. '" ';
        $shape .= $path_params;
        $shape .= '></path>';

        $shape .= '</svg>';

        return $shape;
    }
}
