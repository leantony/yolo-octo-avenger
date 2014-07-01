
import java.net.*;
import java.io.*;
import java.util.*;

public class Client {

    // the client needs a GUI so;
    private chatClient c;
    private String server, username;
    private int port;
    private ObjectInputStream server_input; // to read from the socket
    private ObjectOutputStream server_output; // to write on the socket
    private Socket sock;

    Client(String server, int port, String username) {
        // for just a console session, then set the gui object to null
        this(server, port, username, null);
    }

    Client(String server, int port, String username, chatClient c) {
        super();
        this.c = c;
        this.server = server;
        this.username = username;
        this.port = port;
    }

    // chat now
    public boolean begin_chat() {
        // try to connect to the server
        try {
            sock = new Socket(server, port);
        } catch (IOException e) {
            display_client_msg("Could not connect to the server.\nCheck your hostname/port combination and try again: "
                    + e);
            return false;
        }
        String msg = "The connection to " + sock.getInetAddress() + ":"
                + sock.getPort() + " was successful";
        display_client_msg(msg);

        /* create I/O streams */
        try {
            server_input = new ObjectInputStream(sock.getInputStream());
            server_output = new ObjectOutputStream(sock.getOutputStream());
        } catch (IOException ex) {
            display_client_msg("Encountered an I/O error: " + ex);
            return false;
        }

        // creates the Thread to listen from the server
        new msg_from_server().start();
        try {
            server_output.writeObject(username); // send username to server
        } catch (IOException e) {
            display_client_msg("Encountered an error when trying to log you in : "
                    + e);
            disconnect(); // let them retry
            return false;
        }
        return true;
    }

    // save many println's
    private void display_client_msg(String msg) {
        if (c == null) {
            System.out.println(msg);
        } else {
            c.append(msg + "\n");
        }
    }

    // when a disonnnect is issued
    private void disconnect() {

        if (server_input != null) {
            try {
                server_input.close();
            } catch (IOException e1) {
                display_client_msg("" + e1); //really this isn't required but nway t seems handy 4 debugging;
            }
        }

        if (server_output != null) {
            try {
                server_output.close();
            } catch (IOException e) {
                display_client_msg("" + e);
            }
        }

        if (sock != null) {
            try {
                sock.close();
            } catch (IOException e) {
                display_client_msg("" + e);
            }
        }

        if (c != null) {
            c.connection_error();
        }

    }

    /*
     * To send a message to the server
     */
    void send_msg_to_server(chat_msg_type msg) {
        try {
            server_output.writeObject(msg);
        } catch (IOException e) {
            display_client_msg("Encountered an error sending message to the server: "
                    + e);
        }
    }

    public static void main(String[] args) {
        int server_port_no = 25500;
        String usr_name = "Unknown";
        Scanner s = new Scanner(System.in);
        String server_ip = null;

        try {
            System.out.println("Enter the hostname/ip for the server\n");
            server_ip = s.nextLine();
            System.out.println("Enter the server's listening port\n");
            server_port_no = s.nextInt();
        } catch (Exception e1) {
            System.out.println("Error " + e1);
            System.exit(1);
        }

        s.close();
        // cheking args
        switch (args.length) {
            case 3:
                server_ip = args[2];
            case 2:
                try {
                    server_port_no = Integer.parseInt(args[1]);
                } catch (NumberFormatException e) {
                    System.out.println("You entered an invalid port number.");
                    return;
                }
            case 1:
                usr_name = args[0];
            case 0:
                break;
            // invalid number of arguments
            default:
                return;
        }
        // create the Client object
        Client client = new Client(server_ip, server_port_no, usr_name);
        if (!client.begin_chat()) // if we can't connect
        {
            return;
        }

        // wait for messages from user
        Scanner scan = new Scanner(System.in);
        // loop and wait for message from the user
        while (true) {
            System.out.print("# ");
            // read message from user and break on error
            String msg = null;
            try {
                msg = scan.nextLine();
            } catch (Exception e) {
                break;
            }
            // logic of course
            if (msg.equals("EXIT")) {
                client.send_msg_to_server(new chat_msg_type(chat_msg_type.BYE,
                        ""));
                // disconnect
                break;
            } // get logged in users
            else if (msg.equals("WHO")) {
                client.send_msg_to_server(new chat_msg_type(chat_msg_type.WHO,
                        ""));
            } else { // default to ordinary message
                client.send_msg_to_server(new chat_msg_type(
                        chat_msg_type.CHATMSG, msg));
            }
        }
        // done disconnect
        scan.close();
        client.disconnect();
    }

    // append or println whichever comes
    class msg_from_server extends Thread {

        @Override
        public void run() {
            while (true) {
                try {
                    String msg = (String) server_input.readObject();
                    // if console mode print the message and add back the prompt
                    if (c == null) {
                        System.out.println(msg);
                        System.out.print("# ");
                    } else {
                        c.append(msg);
                    }
                } catch (IOException e) {
                    display_client_msg("Your connection has been closed by the server");
                    // if there's a gui then call the gui function for a failed
                    // connection
                    if (c != null) {
                        c.connection_error();
                    }
                    break;
                } // this gets caught in a telnet but i actually don't know why
                // i think its coz a string wasn't captured from the connection
                // stream
                catch (ClassNotFoundException e2) {
                    System.exit(1);
                }
            }
        }
    }
}
