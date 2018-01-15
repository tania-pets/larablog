<?php
namespace App\Http\Controllers;
use File;
use Validator;

class MazeController extends Controller
{

    private $start;
    private $end;
    private $passed;
    private $imgOut;
    private $path;
    CONST IMAGE_DIR = 'uploads';
    CONST INPUT_FILE = 'in.png';
    CONST OUTPUT_FILE = 'out.png';

    /**
     * Show the form to upload the maze img
     */
    public function index() {
        return view('maze_form');
    }


    /**
     * Upload the maze img, save it and show the results
     * @param \Illuminate\Http\Request  $request
     * @todo make if work for start/end all arround and not only top/bottom
     * @todo colorize the path only without the extra checked pixels
     */
    public function solve(\Illuminate\Http\Request  $request) {
        ini_set('xdebug.max_nesting_level', 200000);
        $img = $this->uploadImage($request);
        if (!$img) {
            return back()->with('message', 'The file must be a png image.');
        }
        if (!$this->setStartEnd($img)) {
            return redirect('/maze')->with('message', 'Wrong maze image. Use a rect maze.');
        } else {
            $r =  $this->findPath($img, $this->start['x'], $this->start['y']);
            return redirect('maze-result');
        }
    }

    /**
     * Shows the solution of the maze
     */
    public function showResult(){
        return view('maze_result',['solved' => 1, 'input' => url('/uploads/' . self::INPUT_FILE), 'output' => url('/uploads/' . self::OUTPUT_FILE) ]);
    }

    /**
     * Sets the private start, end params
     * takes first opening on top and bottom
     * @todo make if work for start/end all arround and not only top/bottom
     */
    private function setStartEnd($img) {
        $file = self::IMAGE_DIR . '/' . self::INPUT_FILE;
        list($width, $height) = getimagesize($file);
        $y =0;
        for ($x = 0 ; $x < $width ; $x++) {
            if ($this->canGo($img, $x, $y)) {
                $start = ['x' => $x, 'y' => $y];
                break;
            }
        }
        $y = $height -1;
        for ($x = 0 ; $x < $width ; $x++) {
            if ($this->canGo($img, $x, $y)) {
                $end = ['x' => $x, 'y' => $y];
                break;
            }
        }
        //wrong image
        if (($start['x'] == 0 && $end['x'] == 0) ) {
            return false;
        }
        $this->start = $start;
        $this->end = $end;
        return true;
    }

    /**
     * Sets a pixel to red color
     * @param int $x, xpos to color
     * @param int $y, ypos to color
     * @return void
     */
    private function setPathColor($x, $y) {
        $red = imagecolorallocate($this->imgOut, 255, 44, 15);
        imagesetpixel($this->imgOut, $x, $y, $red);
    }

    /**
     * Draws the path to outpout img, by colorizing pixels within $this->path
     * @return void
     */
    private function drawPath() {
        foreach($this->path as $x => $ys) {
            foreach($ys as $y => $v){
                $this->setPathColor($x, $y);
            }
        }
        imagepng($this->imgOut, self::IMAGE_DIR . '/' . self::OUTPUT_FILE);
    }

    /**
     * Recursive function to find a maze path
     * @param $img, the gd resource img of the maze
     * @param int $x, xpos to check path
     * @param int $y, ypos to check path
     * @return boolean
     */
    private function findPath($img, $x, $y) {
        //passed already
        if($this->passed && isset($this->passed[$x][$y])){
            return false;
        }
        //The end is found - draw the output
        if ($x == $this->end['x'] && $y == $this->end['y']){
            $this->drawPath();
            return true;
        }
        //if wall return false
        if(!$this->canGo($img, $x, $y )) {
            return false;
        }
        $this->path[$x][$y] = 1;
        $this->passed[$x][$y] = 1;

        //if can move arround
        if ($this->findPath($img, $x, $y - 1)) return true;
        if ($this->findPath($img, $x+1, $y) )return true;
        if ($this->findPath($img, $x, $y +1)) return true;
        if ($this->findPath($img, $x-1, $y)) return true;

        unset($this->path[$x][$y]);
        return false;
    }

    /**
     * Check if robot can got to point - if no wall (=pixel is not black)
     * @param $img, the gd resource img of the maze
     * @param int $x, xpos to check
     * @param int $y, ypos to check
     * @return boolean
     */
    private function canGo($img, $x, $y) {
        try {
            $rgb = imagecolorat($img, $x, $y);
            return $rgb!=0;
        }
        catch (\Exception $e) { //out of bounds
            return false;
        }
    }

    /**
     * Uploades a maze image, crops if needed (when extra empty space arround)
     * and copies to out.png (file to draw the path)
     * @param \Illuminate\Http\Request  $request)
     * @return the gd resource img of the maze
     */
    private function uploadImage(\Illuminate\Http\Request  $request) {
        $input = $request->all();
        $filePath = self::IMAGE_DIR;
        //validate file
        $rules = array(
          'file' => 'required|image|mimes:png|max:3000',
        );
        $validation = Validator::make($input, $rules);
        if ($validation->fails()) {
          return false;
        }
        $file = array_get($input,'file');
        $destinationPath = $filePath;
        $extension = $file->getClientOriginalExtension();
        $fileName = $filePath . '/' . self::INPUT_FILE;
        $file = $upload_success = $file->move($destinationPath, $fileName);
        if ($file) {
            $im = imagecreatefrompng($file);
            //crop the file to remove empty spaces arround - if any
            try {
                $imCropped = imagecropauto($im, IMG_CROP_DEFAULT);
                $croppedFile = $fileName;
                imagepng($imCropped, $croppedFile);
                $im = $imCropped;
                $file = $croppedFile;
            } catch (\Exception $e) {

            }
        }
        //copy file for output
        $outFileName = $filePath . '/' . self::OUTPUT_FILE;
        $outFile = File::copy($file , $outFileName);
        $this->imgOut = imagecreatefrompng($outFileName);

        return $im;
    }


}
