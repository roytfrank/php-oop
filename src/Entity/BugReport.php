<?php

namespace App\Entity;

class BugReport extends Entity{

    private $id;
    private $report_type; 
    private $email; 
    private $link;  
    private $message;  
    private $created_at; 

   public function getId(): int{
       return (int)$this->id;
   }

   public function setReportType(string $reportType){
      $this->report_type  = $reportType;
      return $this;
    }

    /**
     * @return string
     */
   public function getReportType(): string{
     return $this->report_type;
    }   

    public function setEmail(string $email){
        $this->email  = $email;
        return $this;
      }
  
    /**
     * @return string
     */
     public function getEmail(): string{
       return $this->email;
      }   


    public function setLink(?string $link){
    $this->link  = $link;
    return $this;
    }

    /**
     * @return string|null
     */
    public function getLink(): ?string{
    return $this->link;
    }   

    public function setMessage(string $message){
    $this->message  = $message;
    return $this;
    }

    
    public function getMessage(): string{
    return $this->message;
    }   

    public function getCreatedAt(){
      return $this->created_at;
    }

   public function toArray(): array{
        return [
            'report_type' => $this->getReportType(),
            'email' => $this->getEmail(),
            'link' => $this->getLink(),
            'message' => $this->getMessage(),
            'created_at' => date('Y-m-d H:i:s'),
        ];
   }

}



?>