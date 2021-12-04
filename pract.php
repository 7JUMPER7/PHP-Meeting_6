<?php
    trait Collect {
        function Add(&$arr) {
            if(!$this instanceof Geometry) {
                return false;
            } else {
                $arr[] = $this;
            }
        }
    }

    interface Geometry {
        function Perimeter();
        function Area();
    }

    class Point {
        use Collect;
        protected $x;
        protected $y;
        
        function __construct($x, $y) {
            $this->x = $x;
            $this->y = $y;
        }

        function show() {
            echo "<div>X: ".$this->x."\nY: ".$this->y."</div>";
        }
    }

    class Rectangle extends Point implements Geometry {
        //use Collect;
        protected $width;
        protected $height;

        function __construct($x, $y, $width, $height) {
            parent::__construct($x, $y);
            $this->width = $width;
            $this->height = $height;
        }

        function show() {
            echo "<div>X: ".$this->x."<br>Y: ".$this->y."<br>Width: ".$this->width."<br>Height: ".$this->height."</div>";
        }

        function Perimeter() {
            return 2 * ($this->width + $this->height);
        }

        function Area() {
            return $this->width * $this->height;
        }
    }

    class Circle extends Point implements Geometry  {
        //use Collect;
        protected $radius;

        function __construct($x, $y, $radius) {
            parent::__construct($x, $y);
            $this->radius = $radius;
        }

        function show() {
            parent::show();
            echo "Radius: ".$this->radius."</div>";
        }

        function Perimeter() {
            return 2 * pi() * $this->radius;
        }

        function Area() {
            return pi() * $this->radius**2;
        }
    }

    $p1 = new Point(50, 100);
    // $p1->x = 50;
    // $p1->y = 100;
    // $p1->show();
    // var_dump($p1);

    $rect = new Rectangle(50, 100, 77, 56);
    $rect->show();

    $circle = new Circle(50, 100, 25);
    $circle->show();
    echo $circle->Perimeter();
    echo $circle->Area();

    $totalPerimeter = 0;
    $totalArea = 0;
    $arr = array();
    // $arr[] = $rect;
    // $arr[] = $circle;
    $rect->Add($arr);
    $circle->Add($arr);
    $p1->Add($arr);
    foreach($arr as $elem) {
        $totalPerimeter += $elem->Perimeter();
        $totalArea += $elem->Area();
    }
    echo "<div>".$totalPerimeter."</div>";
    echo "<div>".$totalArea."</div>";
?>