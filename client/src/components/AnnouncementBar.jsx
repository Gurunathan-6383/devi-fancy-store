import { useState, useEffect, useRef } from 'react';
import { Link } from 'react-router-dom';
import { announcementAPI } from '../services/api';

const typeEmojis = {
  discount: '🏷️',
  festival: '🎉',
  flash_sale: '⚡',
  new_arrival: '✨',
  free_shipping: '🚚',
  general: '📢',
};

export default function AnnouncementBar() {
  const [announcements, setAnnouncements] = useState([]);
  const [isPaused, setIsPaused] = useState(false);
  const scrollRef = useRef(null);

  useEffect(() => {
    fetchAnnouncements();
    const interval = setInterval(fetchAnnouncements, 60000);
    return () => clearInterval(interval);
  }, []);

  const fetchAnnouncements = async () => {
    try {
      const res = await announcementAPI.getActive();
      setAnnouncements(res.data.data || []);
    } catch {
      setAnnouncements([]);
    }
  };

  if (!announcements.length) return null;

  const duplicated = [...announcements, ...announcements, ...announcements];

  const content = duplicated.map((a, i) => {
    const emoji = typeEmojis[a.type] || '📢';
    const text = (
      <span className="flex items-center space-x-2 whitespace-nowrap">
        <span>{emoji}</span>
        <span className="font-semibold">{a.title}</span>
        <span className="opacity-80">—</span>
        <span>{a.message}</span>
      </span>
    );

    const wrapperClass = "flex items-center space-x-6 px-6 shrink-0";

    if (a.redirect_url) {
      return (
        <a key={`${a.id}-${i}`} href={a.redirect_url} target="_blank" rel="noopener noreferrer" className={wrapperClass}>
          {text}
        </a>
      );
    }
    return <span key={`${a.id}-${i}`} className={wrapperClass}>{text}</span>;
  });

  const singleSetWidth = announcements.length * 350;

  return (
    <div
      className="relative w-full overflow-hidden"
      style={{
        backgroundColor: announcements[0]?.bg_color || '#e04a6f',
        color: announcements[0]?.text_color || '#ffffff',
      }}
      onMouseEnter={() => setIsPaused(true)}
      onMouseLeave={() => setIsPaused(false)}
    >
      <div
        ref={scrollRef}
        className="flex items-center h-10 md:h-11"
        style={{
          animation: `marquee-scroll ${announcements.length * 15}s linear infinite`,
          animationPlayState: isPaused ? 'paused' : 'running',
          width: 'max-content',
        }}
      >
        {content}
      </div>

      <style>{`
        @keyframes marquee-scroll {
          0% { transform: translateX(0); }
          100% { transform: translateX(-${singleSetWidth}px); }
        }
      `}</style>
    </div>
  );
}
