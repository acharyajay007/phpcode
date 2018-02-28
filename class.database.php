<?php 
Class Database{
	private $DBHOST="localhost";
	private $DBNAME="sample_db";
	private $DBUSER="root";
	private $DBPASSWORD="root123";
	private $Connection=null;
	private $DBDriver='PDO';
	private $ErrorLevel='1';
	private $Statement="";
	public function __construct()
	{
		try
		{
			if($this->DBDriver=='mysqli')
			{
				$this->Connection=mysqli_connect($this->DBHOST,$this->DBUSER,$this->DBPASSWORD,$this->DBNAME);
				if(!$this->Connection){
		            throw new Exception("Not Connected To Database ". mysqli_connect_error());
		         }
			}
			else if($this->DBDriver=='PDO')
			{
				$this->Connection=new PDO("mysql:host=".$this->DBHOST.";dbname=".$this->DBNAME,$this->DBUSER,$this->DBPASSWORD);
				if(!$this->Connection){
					throw new Exception("Not Connected To Database ");
		         }
		         else
		         {
		         	$this->Connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT );
					/*$this->Connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
					$this->Connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		            */
		            
		         }
			}
			else
			{
				throw new Exception("Not Suppoted Database driver ");
			}
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
			exit;
		}
		
	}

	public function setDatabaseDriver($DBDriver)
	{
		$this->DBDriver=$DBDriver;
	}

	public function __destruct()
	{
		if($this->Connection)
		{
			if($this->DBDriver=='mysqli')
			{
				mysqli_close($this->Connection);
			}
			else if($this->DBDriver=='PDO')
			{
				$this->Connection=null;
			}
		}
	}	


	public function query($query)
	{
		try
		{
			if($query!="")
			{

				if($this->DBDriver=='mysqli')
				{

					$Resource=mysqli_query($this->Connection,$query);
					if(!$Resource)
					{
						if($this->ErrorLevel==1)
						throw new Exception("Something went wrong with query ".mysqli_error($this->Connection));
					}
				}
				else if($this->DBDriver=='PDO')
				{
					$this->Statement=$this->Connection->prepare($query);
					$this->Statement->execute();
					$Resource=$this->Connection;
				}
				else
				{
					if($this->ErrorLevel==1)
					throw new Exception("Not Suppoted Database driver ");
				}

				return $Resource;
			}
			else
			{
				if($this->ErrorLevel==1)
				throw new Exception("Query should not be empty ");
			}
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
			exit;
		}
		
	}

	public function fetch($Resource,$FetchType='object')
	{
		$Result=[];
		try
		{
			if($Resource)
			{
				if($this->DBDriver=='mysqli')
				{
					if($FetchType=='object')
					{
						while($R=mysqli_fetch_assoc($Resource))
						{
							$Result[]=$R;
						}
					}
					else
					{
						while($R=mysqli_fetch_array($Resource))
						{
							$Result[]=$R;
						}
					}
				}
				else if($this->DBDriver=='PDO')
				{
					if($FetchType=='object')
					$Result=$this->Statement->fetch(PDO::FETCH_ASSOC);
					else 
					$Result=$this->Statement->fetch(PDO::FETCH_NUM);	
				}
				else
				{
					throw new Exception("Not Suppoted Database driver ");
				}

				return $Result;
			}
			else
			{
				if($this->ErrorLevel==1)
				throw new Exception("Query should not be empty ");
			}
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
			exit;
		}
	}
}