"use client"

import { useState } from "react"

const tableData = [
  { id: 1, name: "Alex Johnson", email: "alex@example.com", role: "Developer", status: "Active", salary: "$75,000" },
  { id: 2, name: "Sarah Chen", email: "sarah@example.com", role: "Designer", status: "Active", salary: "$68,000" },
  { id: 3, name: "Mike Rodriguez", email: "mike@example.com", role: "Manager", status: "Inactive", salary: "$85,000" },
  { id: 4, name: "Emma Wilson", email: "emma@example.com", role: "Developer", status: "Active", salary: "$72,000" },
  { id: 5, name: "David Kim", email: "david@example.com", role: "Analyst", status: "Active", salary: "$65,000" },
  { id: 6, name: "Lisa Thompson", email: "lisa@example.com", role: "Designer", status: "Active", salary: "$70,000" },
  { id: 7, name: "James Brown", email: "james@example.com", role: "Developer", status: "Inactive", salary: "$78,000" },
  { id: 8, name: "Anna Garcia", email: "anna@example.com", role: "Manager", status: "Active", salary: "$90,000" },
]

export default function Component() {
  const [hoveredRow, setHoveredRow] = useState<number | null>(null)

  return (
    <div className="min-h-screen bg-black p-8 flex items-center justify-center">
      <div className="w-full max-w-6xl">
        <h1 className="text-3xl font-bold text-white mb-8 text-center">Employee Dashboard</h1>

        <div className="relative overflow-hidden rounded-[5px]">
          {/* Animated gradient border */}
          <div className="absolute inset-0 rounded-[5px] p-[3px] animate-border-glow">
            <div className="absolute inset-0 rounded-[5px] bg-gradient-to-r from-yellow-400 via-purple-500 via-orange-500 to-yellow-400 bg-[length:300%_300%] animate-gradient-border opacity-80"></div>
            <div className="relative w-full h-full bg-black/90 backdrop-blur-xl rounded-[2px] border border-white/5"></div>
          </div>

          {/* Table container with animated shadows */}
          <div className="relative backdrop-blur-xl bg-black/40 border-0 rounded-[2px] overflow-hidden m-[3px] animate-table-shadow">
            <div className="overflow-x-auto">
              <table className="w-full">
                <thead>
                  <tr className="border-b border-white/10 relative">
                    <th className="px-6 py-4 text-left text-sm font-semibold text-white/90 backdrop-blur-sm relative">
                      <div className="absolute inset-0 bg-gradient-to-r from-transparent via-yellow-500/5 to-transparent animate-shimmer"></div>
                      <span className="relative z-10">ID</span>
                    </th>
                    <th className="px-6 py-4 text-left text-sm font-semibold text-white/90 backdrop-blur-sm relative">
                      <div className="absolute inset-0 bg-gradient-to-r from-transparent via-purple-500/5 to-transparent animate-shimmer-delay-1"></div>
                      <span className="relative z-10">Name</span>
                    </th>
                    <th className="px-6 py-4 text-left text-sm font-semibold text-white/90 backdrop-blur-sm relative">
                      <div className="absolute inset-0 bg-gradient-to-r from-transparent via-orange-500/5 to-transparent animate-shimmer-delay-2"></div>
                      <span className="relative z-10">Email</span>
                    </th>
                    <th className="px-6 py-4 text-left text-sm font-semibold text-white/90 backdrop-blur-sm relative">
                      <div className="absolute inset-0 bg-gradient-to-r from-transparent via-yellow-500/5 to-transparent animate-shimmer-delay-3"></div>
                      <span className="relative z-10">Role</span>
                    </th>
                    <th className="px-6 py-4 text-left text-sm font-semibold text-white/90 backdrop-blur-sm relative">
                      <div className="absolute inset-0 bg-gradient-to-r from-transparent via-purple-500/5 to-transparent animate-shimmer-delay-4"></div>
                      <span className="relative z-10">Status</span>
                    </th>
                    <th className="px-6 py-4 text-left text-sm font-semibold text-white/90 backdrop-blur-sm relative">
                      <div className="absolute inset-0 bg-gradient-to-r from-transparent via-orange-500/5 to-transparent animate-shimmer-delay-5"></div>
                      <span className="relative z-10">Salary</span>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  {tableData.map((row, index) => (
                    <tr
                      key={row.id}
                      className={`
  border-b border-white/5 transition-all duration-500 ease-out relative
  hover:bg-white/8 hover:backdrop-blur-md hover:border-white/20
  hover:transform hover:scale-[1.01]
  ${hoveredRow === index ? "bg-white/5 transform scale-[1.01]" : ""}
`}
                      onMouseEnter={() => setHoveredRow(index)}
                      onMouseLeave={() => setHoveredRow(null)}
                    >
                      <td className="px-6 py-4 text-sm text-white/80 font-mono relative">
                        {hoveredRow === index && (
                          <div className="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-yellow-400 via-purple-500 to-orange-500 animate-pulse"></div>
                        )}
                        #{row.id.toString().padStart(3, "0")}
                      </td>
                      <td className="px-6 py-4 text-sm text-white font-medium">{row.name}</td>
                      <td className="px-6 py-4 text-sm text-white/70">{row.email}</td>
                      <td className="px-6 py-4 text-sm">
                        <span className="px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-yellow-400/20 to-orange-400/20 text-yellow-300 border border-yellow-400/30 hover:shadow-[0_0_15px_rgba(251,191,36,0.5)] transition-all duration-300">
                          {row.role}
                        </span>
                      </td>
                      <td className="px-6 py-4 text-sm">
                        <span
                          className={`
                  px-3 py-1 rounded-full text-xs font-medium border transition-all duration-300
                  ${
                    row.status === "Active"
                      ? "bg-green-500/20 text-green-300 border-green-400/30 hover:shadow-[0_0_15px_rgba(34,197,94,0.5)]"
                      : "bg-red-500/20 text-red-300 border-red-400/30 hover:shadow-[0_0_15px_rgba(239,68,68,0.5)]"
                  }
                `}
                        >
                          {row.status}
                        </span>
                      </td>
                      <td className="px-6 py-4 text-sm text-white font-semibold">{row.salary}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div className="mt-6 text-center text-white/60 text-sm">Hover over rows to see the glassmorphic effects</div>
      </div>
    </div>
  )
}
