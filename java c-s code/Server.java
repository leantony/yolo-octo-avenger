
import java.awt.HeadlessException;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.io.Serializable;
import java.net.InetAddress;
import java.net.ServerSocket;
import java.net.Socket;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Vector;

import javax.swing.JOptionPane;

public class Server {

    private int port_number;
    private InetAddress addr;
    private static int client_id; // track connections to the server
    // is server running or not ; true/false respectively
    private boolean is_running;
    // vector for storage of connected clients
    private Vector<thread_of_client> client;
    @SuppressWarnings("unused")
    private Object g = null;
    // this format will do us good
    private SimpleDateFormat dt = new SimpleDateFormat("HH:mm:ss");
    private Socket client_sock; // client
    private ServerSocket sock; // server

    // private static int timeout = 10;
    public Server(int port) {
        this(port, null);
    }

    // if we ever need a gui for the server, then this would come in handy
    public Server(int port, Object gui) {
        this.g = gui;
        this.port_number = port;
        // initialize the vector array
        client = new Vector<>();
    }

    // starts the server
    public void start() {
        is_running = true;
        /*
         * let user define the socket and hostname used by the server exit on
         * error
         */
        String ip = null;
        try {
            ip = JOptionPane
                    .showInputDialog(
                            null,
                            "Now enter the hostname/ip for the listening interface\n"
                            + "e.g 127.0.0.1, localhost, mydomain.com, or even your computers name",
                            "127.0.0.1");
        } catch (Exception e1) {
            print_msg("Try again coz the server cant proceed. Error is " + e1);
            System.exit(1);
        }

        sock = null;
        // try catch to check for invalid hostnames,etc
        try {
            // sock.setSoTimeout(timeout);
            addr = InetAddress.getByName(ip);
            sock = new ServerSocket(port_number, 20, addr);
        } catch (IOException e1) {
            print_msg("Try again because the server can't proceed. Error is "
                    + e1);
            System.exit(1);
        }
        // if successful which is the case if
        // the code reaches here, then display this on the console
        JOptionPane.showMessageDialog(null,
                "Now, take the port & hostname you typed initially\n"
                + "and use em on a client "
                + "to initialize a connection", "Success",
                JOptionPane.INFORMATION_MESSAGE);
        print_msg("the server is listening for connections on "
                + sock.getInetAddress() + ":" + sock.getLocalPort());

        // infinite loop to wait for connections
        while (is_running) {
            // accept client connection
            client_sock = null;
            try {
                client_sock = sock.accept();
            } catch (IOException e) {
                print_msg("Your connection was't accepted by server. Error is "
                        + e);
            }
            // get out of loop if needed
            if (!is_running) {
                break;
            }

            // otherwise create a thread instance of the client connection
            thread_of_client t = new thread_of_client(client_sock);
            client.add(t); // and save it in the vector array
            // use
            t.start(); // then start the thread
        }
        // come here when server is supposed to stop
        try {
            sock.close();
            // close client connections
            for (int i = 0; i < client.size(); ++i) {
                thread_of_client tc = client.get(i);
                try {
                    tc.server_input.close();
                    tc.server_output.close();
                    tc.socket.close();
                } catch (IOException x) {
                    print_msg("Could not close all client connections. Error is "
                            + x);
                }
            }
        } catch (IOException e) {
            print_msg("Encounterd an error when closing the server and disconnecting clients: "
                    + e);
        }
    }

    // saves the task of having to system.out all the time
    private void print_msg(String msg) {
        String time_and_msg = dt.format(new Date()) + " " + msg;
        System.out.println(time_and_msg);
    }

    /*
     * Broadcasts section **** Broadcasts simply travel to all clients connected
     * an example would be a sever shutdown or error in this case, ours can be
     * the chat messages also and they are synchronized across the thread
     * instances
     */
    private synchronized void broadcast(String brodcast_msg) {
        String time = dt.format(new Date());
        System.out.print("broadcast at " + time + " >" + brodcast_msg + "\n");
        // reverse loop for checking for unavailable clients
        for (int i = client.size(); --i >= 0;) {
            thread_of_client c = client.get(i);
            // if a broadcast can't pass through to a connected
            // client then remove it from the list
            if (!c.chat(time + " " + brodcast_msg + "\n")) {
                client.remove(i);
                print_msg("client " + c.client_usrname
                        + " has disconnected is no longer available.");
            }
        }
    }

    // remove a user from the vector array who exits e.g by closing the chat
    // window or logging out
    synchronized void remove(int id) {
        for (int i = 0; i < client.size(); ++i) {
            thread_of_client ct = client.get(i);
            if (ct.id == id) {
                client.remove(i);
                return;
            }
        }
    }

    // stop server
    protected void stop() {
        is_running = false;
        try {
        	 // tried a broadcast to all users to inform them of a shutdown, but makes perfect
        	// sense if the server had a GUI
        	broadcast("The server has been stopped");
        	sock.close();
        } catch (IOException e) {
            print_msg("Encountred an error while shutting down the server: "
                    + e);
        }
    }

    /* the main aka entry point for the program is here */
    public static void main(String[] args) {
        // start server on port 25500 unless specified
        int port_number = 25500;
        // nimbus look and feel
        try {
            for (javax.swing.UIManager.LookAndFeelInfo info : javax.swing.UIManager
                    .getInstalledLookAndFeels()) {
                if ("Nimbus".equals(info.getName())) {
                    javax.swing.UIManager.setLookAndFeel(info.getClassName());
                    break;
                }
            }
        } catch (ClassNotFoundException | InstantiationException | IllegalAccessException | javax.swing.UnsupportedLookAndFeelException ex) {
            java.util.logging.Logger.getLogger(Server.class.getName()).log(
                    java.util.logging.Level.SEVERE, null, ex);
        }
        try {
            port_number = Integer.parseInt(JOptionPane.showInputDialog(null,
                    "specify a port for the server to listen on.\n"
                    + "e.g 25500 or proceed with 0 which \n "
                    + "generates a random port", "0"));
        } catch (NumberFormatException | HeadlessException e1) {
            System.out.println(e1);
            System.exit(1);
        }
        // this would help in a console session where args would be needed or
        // for a telnet connection which refuses to work in this case
        switch (args.length) {
            // 1 arg specified
            case 1:
                try { // check if arg supplied is a port (integer)
                    port_number = Integer.parseInt(args[0]);
                } catch (NumberFormatException e) {
                    System.out.println("an invalid port number was specified.");
                    System.out
                            .println("goto the compiled java classes directory\nthen type java Server 25500");
                    System.exit(1);
                }
            case 0:
                break;
            default:
                System.out
                        .println("goto the compiled java classes directory\nthen type java Server 25500");
                System.exit(1);

        }
        // a valid start
        Server s = new Server(port_number);
        s.start();
    }

    /*
     * program has one thread but an instance of this thread will run for each
     * client who connects
     */
    class thread_of_client extends Thread {

        // the socket where to listen/talk
        Socket socket;
        ObjectInputStream server_input;
        ObjectOutputStream server_output;
        int id;
        String client_usrname;
        chat_msg_type cm; // chat object message
        String date;

        thread_of_client(Socket socket) {
            // increment dis to mek t uniq e.g in the instance where two users
            // have similar usernames
            id = ++client_id;
            this.socket = socket;
            print_msg("I/O is taking place");
            try {
                // create output first
                server_output = new ObjectOutputStream(socket.getOutputStream());
                server_input = new ObjectInputStream(socket.getInputStream());
                // username
                client_usrname = (String) server_input.readObject();
                // print_msg(client_usrname + " has just connected.");
                broadcast(client_usrname + " has has logged in from "
                        + client_sock.getInetAddress());
            } catch (IOException e) {
                print_msg("Server encounered an error sending data: " + e);
                return;
            } catch (ClassNotFoundException e) {
            }
            date = new Date().toString() + "\n";
        }

        // send chat messages to client
        private boolean chat(String msg) {
            // this is only possible if they ar connected
            if (!socket.isConnected()) {
                close();
                return false;
            }
            // if they are available, write msg to output stream
            try {
                server_output.writeObject(msg);
            } catch (IOException e) {
                // print the error to the console
                print_msg(client_usrname + " : Error sending message "
                        + e.toString());
            }
            return true;
        }

        // runs until user logoff
        @Override
        public void run() {
            // to loop until EXIT
            boolean server_running = true;
            while (server_running) {
                // read a String (which is an object)
                try {
                    cm = (chat_msg_type) server_input.readObject();
                } catch (IOException e) {
                    print_msg(client_usrname
                            + " has encountered an I/O error. Looks like its a sudden reset"
                            + e);
                    broadcast(client_usrname + " has disconnected");
                    break;
                } catch (ClassNotFoundException e2) { //just catch it!
                    break;
                }
                // chat
                String message = cm.getMessage();

                // capture a variety of this chat messages
                switch (cm.getType()) {

                    case chat_msg_type.CHATMSG:
                        // send a chat everywhere
                        broadcast(client_usrname + ": " + message);
                        break;
                    case chat_msg_type.BYE:
                        // print_msg(client_usrname + " has logged off");
                        broadcast(client_usrname + " has logged off at "
                                + dt.format(new Date()));
                        server_running = false;
                        break;
                    case chat_msg_type.WHO:
                        chat("users connected at " + dt.format(new Date()) + "\n");
                        // scan all the users connected
                        for (int i = 0; i < client.size(); ++i) {
                            thread_of_client ct = client.get(i);
                            chat((i + 1) + ") " + ct.client_usrname + " since "
                                    + ct.date);
                        }
                        break;
                }
            }
            // we don't need the host being shown among the connected clients
            remove(id);
            close();
        }

        // try to close everything
        private void close() {
            // try to close the connection
            try {
                if (server_output != null) {
                    server_output.close();
                }
            } catch (IOException e) {
            }
            try {
                if (server_input != null) {
                    server_input.close();
                }
            } catch (IOException e) {
            }
            try {
                if (socket != null) {
                    socket.close();
                }
            } catch (IOException e) {
            }
        }

    }
}

// WHO shows connected users when invoked by client/server
// message is a chat message
// EXIT logs a user who invokes it out of the system
class chat_msg_type implements Serializable {

    /**
     *
     */
    private static final long serialVersionUID = -3875380843739221253L;
    static final int WHO = 0;
    static final int CHATMSG = 1;
    static final int BYE = 2;
    private final int type;
    private final String message;

    chat_msg_type(int type, String message) {
        this.type = type;
        this.message = message;
    }

    int getType() {
        return type;
    }

    String getMessage() {
        return message;
    }

}
