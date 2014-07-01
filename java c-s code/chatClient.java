import javax.swing.*;
import java.awt.*;
import java.awt.event.*;

/*
 * The client with its GUI
 */
public class chatClient extends JFrame implements ActionListener,
		WindowListener {

	/**
	 *
	 */
	private static final long serialVersionUID = -2381442266106318158L;
	private final JLabel lblusr;
	private final JTextArea chat_results;
	private boolean connected;
	private final JTextField clientUsrname;
	private final JTextArea chatmsg;
	private final JTextField txtServerAddress, txtServerPort;
	private final JButton connect, exit, check_users, send;
	private Client me; // me, the client object

	private final int port;
	private final String hostname;

	chatClient(String host, int port) {
		super("The chat client by //antony,moses and peter");
		this.port = port;
		hostname = host;

		// The top panel where we'll have the username, server and port inputs:
		JPanel top = new JPanel(new GridLayout(3, 1));
		JPanel server_info = new JPanel(new GridLayout(1, 5, 1, 3));
		txtServerAddress = new JTextField(host);
		txtServerAddress.setHorizontalAlignment(SwingConstants.CENTER);
		txtServerPort = new JTextField(port);
		txtServerPort.setHorizontalAlignment(SwingConstants.CENTER);
		server_info.add(new JLabel("Hostname:  "));
		server_info.add(txtServerAddress);
		server_info.add(new JLabel("Port No:  "));
		server_info.add(txtServerPort);
		server_info.add(new JLabel(""));
		top.add(server_info);
		lblusr = new JLabel("Enter your username below", SwingConstants.CENTER);
		lblusr.setToolTipText("any name can do. just dont leave it blank");
		txtServerAddress.setText(host);
		txtServerAddress
				.setToolTipText("Enter the server's hostname. e.g 127.0.0.1, mydomain.com");
		txtServerPort.setToolTipText("Enter the server's listening port");
		txtServerPort.setText("" + port);
		top.add(lblusr);
		clientUsrname = new JTextField("unknown");
		top.add(clientUsrname);
		add(top, BorderLayout.NORTH);

		// this chat panel will appear at the center of the layout and holds the
		// chat and results
		chat_results = new JTextArea(80, 80);
		chatmsg = new JTextArea(80, 80);
		JPanel chat = new JPanel(new GridLayout(2, 2));
		chat.add(new JScrollPane(chat_results));
		chat.add(chatmsg);
		chat.add(new JScrollPane(chatmsg));
		chat_results.setEditable(false); // simple logic applies here of course
		chat_results.setText("the chat results/events will appear here\n");
		chat_results.setToolTipText("the chat results will appear here");
		chatmsg.setText("Enter a message here\n");
		chatmsg.setEditable(false); // no chats till login
		chatmsg.setToolTipText("Enter a message here and press \"send\" button below to send it");
		add(chat, BorderLayout.CENTER);

		// buttons
		connect = new JButton("connect");
		connect.addActionListener(this);
		exit = new JButton("exit");
		exit.setToolTipText("connect to the server");
		exit.addActionListener(this);
		exit.setEnabled(false); // logic!
		check_users = new JButton("users");
		check_users.setToolTipText("view other connected clients");
		check_users.addActionListener(this);
		check_users.setEnabled(false); // logic!
		send = new JButton("send");
		send.setToolTipText("sends a message to the server");
		send.setEnabled(false);
		send.addActionListener(this);

		// add the buttons to the bottom panel
		JPanel bottom = new JPanel();
		bottom.add(connect);
		bottom.add(exit);
		bottom.add(check_users);
		bottom.add(send);
		add(bottom, BorderLayout.SOUTH);

		setDefaultCloseOperation(DO_NOTHING_ON_CLOSE);
		setLocationByPlatform(true);
		setSize(550, 600);
		clientUsrname.requestFocus();
		// someone chooses to close the window we need to close all connections
		// anyway
		addWindowListener(this);

	}

	// called by the Client to append text in the TextArea
	void append(String str) {
		chat_results.append(str);
		chat_results.setCaretPosition(chat_results.getText().length() - 1);
	}

	// enable/disable relevant actions if connection fails/is stopped
	void connection_error() {
		connect.setEnabled(true);
		exit.setEnabled(false);
		check_users.setEnabled(false);
		send.setEnabled(false);
		lblusr.setText("Enter your username below");
		clientUsrname.setEnabled(true);
		clientUsrname.setText("unknown");
		txtServerPort.setText("" + port);
		txtServerAddress.setText(hostname);
		// let the user change them
		txtServerAddress.setEditable(true);
		txtServerPort.setEditable(true);
		//chat_results.setText("the chat results/events will appear here"); // no need to blank out the event messages part
		chatmsg.setText("Enter a message here");
		chatmsg.setEditable(false);
		// leave the event handler for sending a msg since its not needed 4 now
		send.removeActionListener(this);
		connected = false;
	}

	// button events. this is an actionperformed insted of having an
	// actionlistener on all buttons
	@Override
	public void actionPerformed(ActionEvent e) {
		Object eventsrc = e.getSource();
		// checking logged in users
		if (eventsrc == check_users) {
			me.send_msg_to_server(new chat_msg_type(chat_msg_type.WHO, ""));
			return;
		}

		// if it is the exit button
		if (eventsrc == exit) {
			me.send_msg_to_server(new chat_msg_type(chat_msg_type.BYE, ""));
			send.setEnabled(false);
			return;
		}

		// chatting
		if (eventsrc == send) {
			if (connected) {
				me.send_msg_to_server(new chat_msg_type(chat_msg_type.CHATMSG,
						chatmsg.getText()));
				// clear the chat text area on successful send
				chatmsg.setText("");
				return;
			}
		}

		// i want to connect
		// ignore user errors on input
		if (eventsrc == connect) {
			String username = clientUsrname.getText().trim();
			if (username.length() == 0) {
				return;
			}
			String server = txtServerAddress.getText().trim();
			if (server.length() == 0) {
				return;
			}
			String portNumber = txtServerPort.getText().trim();
			if (portNumber.length() == 0) {
				return;
			}
			int p;
			try {
				p = Integer.parseInt(portNumber);
			} catch (NumberFormatException en) {
				// invalid port
				JOptionPane
						.showMessageDialog(
								null,
								"A wrong value of the port was specified\nports are integer values running from [1-65535]\nso Please retry",
								"Error", JOptionPane.ERROR_MESSAGE);
				txtServerPort.requestFocus();
				return;
			}

			// create a client object
			me = new Client(server, p, username, this);
			// then start chat session
			if (!me.begin_chat()) {
				return;
			}
			chatmsg.setText("");
			connected = true;
			lblusr.setText("Now you can chat thru the chat window");
			clientUsrname.setEnabled(false);
			connect.setEnabled(false); // why do you need to reconnect again?
			txtServerAddress.setEditable(false);
			txtServerPort.setEditable(false);
			exit.setEnabled(true); // then enable this
			check_users.setEnabled(true);
			send.setEnabled(true);
			chatmsg.setEditable(true);
		}

	}

	// entry point
	public static void main(String[] args) {
		// nimbus look and feel
		try {
			for (javax.swing.UIManager.LookAndFeelInfo info : javax.swing.UIManager
					.getInstalledLookAndFeels()) {
				if ("Nimbus".equals(info.getName())) {
					javax.swing.UIManager.setLookAndFeel(info.getClassName());
					break;
				}
			}
		} catch (ClassNotFoundException | InstantiationException
				| IllegalAccessException
				| javax.swing.UnsupportedLookAndFeelException ex) {
			java.util.logging.Logger.getLogger(chatClient.class.getName()).log(
					java.util.logging.Level.SEVERE, null, ex);
		}
		new chatClient("127.0.0.1", 25500).setVisible(true);
	}

	@Override
	public void windowClosing(WindowEvent e) {
		int reply = JOptionPane.showConfirmDialog(null, "Are you sure",
				"prompt", JOptionPane.INFORMATION_MESSAGE,
				JOptionPane.OK_CANCEL_OPTION);
		if (reply == JOptionPane.CANCEL_OPTION | reply == JOptionPane.NO_OPTION) {
			return;
		} else {
			dispose();
			System.exit(0);
		}
	}

	@Override
	public void windowClosed(WindowEvent e) {
	}

	@Override
	public void windowIconified(WindowEvent e) {
	}

	@Override
	public void windowDeiconified(WindowEvent e) {
	}

	@Override
	public void windowActivated(WindowEvent e) {
	}

	@Override
	public void windowDeactivated(WindowEvent e) {
	}

	@Override
	public void windowOpened(WindowEvent e) {

	}
}
