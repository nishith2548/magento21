pipeline
{
    agent  any
    stages
    {
        
       
        stage ("syntax error check")
        {
            steps
            {
                echo "This is Demo"
            }
        }
	 
	/*stage ("sonar")
        {
            steps
            {
                //sh 'sonar-scanner -Dsonar.projectKey="abc" -Dsonar.sources=.'
                sh '/opt/sonar-scanner-3.3.0.1492-linux/bin/sonar-scanner'
            }
        } */
	
	    stage ("Image Build")
	    {
	        steps
	        {
		          sh 'docker build -t magento21 .' //build image
	        }
	    }
  
  
	    stage('Image Run')
      {
          steps
          {
            sh 'docker rm -f cont2'
            sh 'docker run --name cont2 -i -d -p 9095:80 image1 '
          }
      }
  }  

    
}
