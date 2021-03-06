<?php namespace Maknz\Slack;

use InvalidArgumentException;

class Message {

  /**
   * Reference to the Slack client responsible for sending
   * the message
   *
   * @var \Maknz\Slack\Client
   */
  protected $client;

  /**
   * The text to send with the message
   *
   * @var string
   */
  protected $text;

  /**
   * The channel the message should be sent to
   *
   * @var string
   */
  protected $channel;

  /**
   * The username the message should be sent as
   *
   * @var string
   */
  protected $username;

  /**
   * The URL to the icon to use
   *
   * @var string
   */
  protected $icon;

  /**
   * The type of icon we are using
   *
   * @var enum
   */
  protected $iconType;

  /**
   * An array of attachments to send
   *
   * @var array
   */
  protected $attachments = [];

  /**
   *
   * @var string
   */
  const ICON_TYPE_URL = 'icon_url';

  /**
   *
   * @var string
   */
  const ICON_TYPE_EMOJI = 'icon_emoji';

  /**
   * Instantiate a new Message
   *
   * @param \Maknz\Slack\Client $client
   * @return void
   */
  public function __construct(Client $client)
  {
    $this->client = $client;
  }

  /**
   * Get the message text
   *
   * @return string
   */
  public function getText()
  {
    return $this->text;
  }

  /**
   * Set the message text
   *
   * @param string $text
   * @return $this
   */
  public function setText($text)
  {
    $this->text = $text;

    return $this;
  }

  /**
   * Get the channel we will post to
   *
   * @return string
   */
  public function getChannel()
  {
    return $this->channel;
  }

  /**
   * Set the channel we will post to
   *
   * @param string $channel
   * @return $this
   */
  public function setChannel($channel)
  {
    $this->channel = $channel;

    return $this;
  }

  /**
   * Get the username we will post as
   *
   * @return string
   */
  public function getUsername()
  {
    return $this->username;
  }

  /**
   * Set the username we will post as
   *
   * @param string $username
   * @return $this
   */
  public function setUsername($username)
  {
    $this->username = $username;

    return $this;
  }

  /**
   * Get the icon (either URL or emoji) we will post as
   *
   * @return string
   */
  public function getIcon()
  {
    return $this->icon;
  }

  /**
   * Set the icon (either URL or emoji) we will post as.
   *
   * @param string $icon
   * @return this
   */
  public function setIcon($icon)
  {
    if ($icon == null)
    {
      $this->icon = $this->iconType = null;

      return;
    }

    if (mb_substr($icon, 0, 1) == ":" && mb_substr($icon, mb_strlen($icon) - 1, 1) == ":")
    {
      $this->iconType = self::ICON_TYPE_EMOJI;
    }

    else
    {
      $this->iconType = self::ICON_TYPE_URL;
    }

    $this->icon = $icon;

    return $this;
  }

  /**
   * Get the icon type being used, if an icon is set
   *
   * @return string
   */
  public function getIconType()
  {
    return $this->iconType;
  }

  /**
   * Change the name of the user the post will be made as
   *
   * @param string $username
   * @return $this
   */
  public function from($username)
  {
    $this->setUsername($username);

    return $this;
  }

  /**
   * Change the channel the post will be made to
   *
   * @param string $channel
   * @return $this
   */
  public function to($channel)
  {
    $this->setChannel($channel);

    return $this;
  }

  /**
   * Chainable method for setting the icon
   *
   * @param string $icon
   * @return $this
   */
  public function withIcon($icon)
  {
    $this->setIcon($icon);

    return $this;
  }

  /**
   * Add an attachment to the message
   *
   * @param mixed $attachment
   * @return $this
   */
  public function attach($attachment)
  {
    if ($attachment instanceof Attachment)
    {
      $this->attachments[] = $attachment;

      return $this;
    }

    elseif (is_array($attachment))
    {
      $attachment = new Attachment($attachment);

      $this->attachments[] = $attachment;

      return $this;
    }

    throw new InvalidArgumentException('Attachment must be an instance of Maknz\\Slack\\Attachment or a keyed array');
  }

  /**
   * Get the attachments for the message
   *
   * @return array
   */
  public function getAttachments()
  {
    return $this->attachments;
  }

  /**
   * Set the attachments for the message
   *
   * @param string $attachments
   * @return $this
   */
  public function setAttachments(array $attachments)
  {
    $this->clearAttachments();

    foreach ($attachments as $attachment)
    {
      $this->attach($attachment);
    }

    return $this;
  }

  /**
   * Remove all attachments for the message
   *
   * @return $this
   */
  public function clearAttachments()
  {
    $this->attachments = [];

    return $this;
  }

  /**
   * Send the message
   *
   * @param string $text The text to send
   * @return void
   */
  public function send($text = null)
  {
    if ($text) $this->setText($text);

    $this->client->sendMessage($this);
  }

}