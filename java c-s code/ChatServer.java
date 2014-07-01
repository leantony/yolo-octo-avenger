import java.net.*;
import java.io.*;

public class ChatServer {
	public static void main(String[] args) throws IOException {
		//initialize socket to null
		ServerSocket server = null;
		try {
			server = new ServerSocket(12000);
		} catch (IOException e) {
			//cannot listen on port specified so we exit
			System.out.println("cannot listen on port 12000");
			System.exit(1);
		}
		
		//set up a client socket
		Socket client = null;
		System.out.println("listening for a connection on port 12000");
		//proceed to accept connection from client
		try {
			client = server.accept();
		} catch (Exception e) {
			//accept connection failed
			System.out.println("couldn't accept connection from client");
			System.exit(1);
		}
		//deploy I/O streams
		BufferedReader input = new BufferedReader(new InputStreamReader(
				client.getInputStream()));
		PrintStream output = new PrintStream(client.getOutputStream());
		BufferedReader serverInput = new BufferedReader(new InputStreamReader(
				System.in));
		//read input while server is running
		//if user types bye then exit and mop up
		String line;
		while (true) {
			line = input.readLine();
			if (line.equals("bye")) {
				break;
			}
			//this is wot will appear on the console
			System.out.println("Client> " + line);
			System.out.print("Server> ");
			line = serverInput.readLine();
			output.println(line);
		}
		server.close();
		client.close();
		input.close();
		output.close();
		serverInput.close();
	}
}