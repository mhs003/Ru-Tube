<?php
/**
 * Youtube Direct
 * @auth: Monzurul Hasan
 * @file: logic.php
 * @class: logic
 * @date: 5/11/2020
 */

class logic{
  public $id = null;

  public function __construct(){
    
  }

  public function getId($url){
    if(filter_var($url, FILTER_VALIDATE_URL)){
      parse_str(parse_url($url, PHP_URL_QUERY), $param);
    
      if($param['v']){
        $this->id = $param['v'];
        return $param['v'];
      } else {
        $exu = explode('/', $url);
        $this->id = $exu[3];
        return $exu[3];
      }
    } else {
      return false;
    }
  }
  
  public function getVideoInfo(){
    $id = $this->id;
    $data = $format_arr = $aformat_arr = array();
    
    if($id != null){
      $info_get = file_get_contents("https://youtube.com/get_video_info?video_id={$id}");
      parse_str($info_get, $info);
      
      $info_json = json_decode($info['player_response'], true);
      $video_status = $info_json['playabilityStatus'];
      if(strtolower($video_status['status']) == 'ok'){
        // -- VIDEO FOUND --
        
        $stream_data = $info_json['streamingData'];
        $formats = $stream_data['formats'];
        $adaptiveFormats = $stream_data['adaptiveFormats'];
        
        $videoDetails = $info_json['videoDetails'];
        $title = $videoDetails['title'];
        $count_thumbnail = count($videoDetails['thumbnail']['thumbnails']) - 1;
        $thumbnail = $videoDetails['thumbnail']['thumbnails'][$count_thumbnail]['url'];
        $i = 0;
        foreach ($formats as $s){
          $fsURL = $s['url'];
          $fsType = explode(';', $s['mimeType']);
          $fsType = $fsType[0];
          $fsSize = 'N/A';
          if(!empty($s['contentLength'])){
            $fsSize = $s['contentLength'];
          }
          $fsQLabel = '';
          if(!empty($s['qualityLabel'])){
            $fsQLabel = $s['qualityLabel'];
          }
          
          $format_arr[$i] = [
            'no' => $i,
            'type' => 'video',
            'url' => $fsURL,
            'mimeType' => $fsType,
            'Content-Length' => $fsSize,
            'quality' => $fsQLabel
          ];
          $i++;
        }
        
        $j = 0;
        foreach ($adaptiveFormats as $as){
          $afsURL = $as['url'];
          $afsType = explode(';', $as['mimeType']);
          $afsType = $afsType[0];
          $afsSize = 0;
          if(!empty($as['contentLength'])){
            $afsSize = $as['contentLength'];
          }
          $afsQLabel = '';
          if(!empty($as['qualityLabel'])){
            $afsQLabel = $as['qualityLabel'];
          }
          
          if($this->startsWith($afsType, 'audio')){
            $afType = 'audio';
            $afQual = $as['audioQuality'];
          } elseif($this->endsWith(strtolower($afsType), 'mp4')){
            $afType = 'video-only';
            $afQual = $afsQLabel;
          } else {
            $afType = 'video';
            $afQual = $afsQLabel;
          }
          
          $aformat_arr[$j] = [
            'no' => $j,
            'type' => $afType,
            'url' => $afsURL,
            'mimeType' => $afsType,
            'Content-Length' => $afsSize,
            'quality' => $afQual
          ];
          $j++;
        }
        
        $data = [
          'error' => false,
          'title' => $title,
          'thumbnail' => $thumbnail,
          'formats' => $format_arr,
          'adaptive_formats' => $aformat_arr
        ];
        
        // -- VIDEO FOUND --
      } elseif($video_status['reason']) {
        $data = [
          'error' => true,
          'reason' => $video_status['reason']
        ];
      } else {
        $data = [
          'error' => true,
          'reason' => "Video unavailable"
        ];
      }
    } else {
      $data = [
          'error' => true,
          'reason' => "Error searching video!"
        ];
    }
    
    return $data;
  }
  
  public function download($title, $url, $size){
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.$title.'"');
    header('Pragma: public');
    header('Content-Length: ' . $size);
    readfile($url);
  }
  
  public function startsWith($string, $startString){
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString); 
  }

  public function endsWith($string, $endString){
    $mLen = strlen($string);
    $eLen = strlen($endString);
    return (substr($string, $mLen - $eLen) === $endString);
  }
}