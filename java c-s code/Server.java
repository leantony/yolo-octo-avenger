
import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import java.net.ServerSocket;
import java.net.Socket;

import javax.swing.JOptionPane;
 
public class Server 
{
 
    private static Socket socket;
 
    public static void main(String[] args) 
    {
        try
        {
 
            int port = 25002;
            ServerSocket serverSocket = new ServerSocket(port);
            System.out.println("Server Started and listening to the port "+ port);
 
            //Server is running always. This is done using this while(true) loop
            while(true) 
            {
                //Reading the message from the client
                socket = serverSocket.accept();
                InputStream is = socket.getInputStream();
                InputStreamReader isr = new InputStreamReader(is);
                BufferedReader br = new BufferedReader(isr);
                String number = br.readLine();
                System.out.println("Message received from client is "+number);
 
                
                String returnMessage = null;
                try
                {
                	returnMessage = JOptionPane.showInputDialog(null, "enter a message to the client");
                }
                catch(NullPointerException e)
                {
                    //disallow blanks
                	JOptionPane.showInputDialog(null, "no empty text allowed");
                }
 
                //Sending the response back to the client.
                
                OutputStream os = socket.getOutputStream();
                OutputStreamWriter osw = new OutputStreamWriter(os);
                BufferedWriter bw = new BufferedWriter(osw);
                bw.write(returnMessage);
                System.out.println("Message sent to the client is "+returnMessage);
                bw.flush();
            }
        }
        catch (Exception e) 
        {
            e.getMessage();
        }
        finally
        {
            try
            {
                socket.close();
            }
            catch(Exception e){}
        }
    }
}